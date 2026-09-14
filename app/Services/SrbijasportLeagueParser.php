<?php

namespace App\Services;

use DOMDocument;
use DOMNode;
use DOMXPath;

/**
 * Parses a league page of srbijasport.net (srbijasport.net/league/<id>-<slug>):
 * the standings table (`table.ssnet-table[layout=standings]`) and the fixtures
 * of the round the page is showing (`div.game-row`, one round per page).
 *
 * The page is Latin already. The round number comes from the `round` attribute
 * of the standings widget, falling back to the "N. kolo" round picker.
 */
class SrbijasportLeagueParser
{
    /**
     * @return array{
     *     round: ?int,
     *     standings: list<array{position:int,team:string,played:int,won:int,drawn:int,lost:int,goals_for:int,goals_against:int,points:int}>,
     *     matches: list<array{round:int,date:?string,time:?string,home:string,away:string,home_score:?int,away_score:?int}>
     * }
     */
    public function parse(string $html): array
    {
        $xpath = $this->xpath($html);
        $round = $this->round($xpath);

        return [
            'round' => $round,
            'standings' => $this->standings($xpath),
            'matches' => $round === null ? [] : $this->matches($xpath, $round),
        ];
    }

    private function xpath(string $html): DOMXPath
    {
        $doc = new DOMDocument;
        libxml_use_internal_errors(true); // Tailwind class soup with "!" and ":" trips the HTML4 parser; expected noise
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        return new DOMXPath($doc);
    }

    private function round(DOMXPath $xpath): ?int
    {
        $widget = $xpath->query('//*[@id="league_tab"]/@round')->item(0);

        if ($widget && ctype_digit(trim($widget->nodeValue))) {
            return (int) $widget->nodeValue;
        }

        $picker = $xpath->query('//*['.$this->hasClass('date-nav-trigger').']')->item(0);

        return $picker && preg_match('/(\d+)\s*\.\s*kolo/iu', $picker->textContent, $m) ? (int) $m[1] : null;
    }

    /** One `tr[tid]` per team; cells are addressed by their `col-*` class. */
    private function standings(DOMXPath $xpath): array
    {
        $rows = [];

        foreach ($xpath->query('//table['.$this->hasClass('ssnet-table').']//tr[@tid]') as $tr) {
            $team = $this->text($xpath, $tr, './/*['.$this->hasClass('team-name').']');

            if ($team === '') {
                continue;
            }

            $position = $this->text($xpath, $tr, './/*['.$this->hasClass('pos-deleg').']');

            $rows[] = [
                'position' => ctype_digit($position) ? (int) $position : count($rows) + 1,
                'team' => $team,
                'played' => $this->number($xpath, $tr, 'col-UTAKM'),
                'won' => $this->number($xpath, $tr, 'col-POB'),
                'drawn' => $this->number($xpath, $tr, 'col-NER'),
                'lost' => $this->number($xpath, $tr, 'col-POR'),
                'goals_for' => $this->number($xpath, $tr, 'col-DG'),
                'goals_against' => $this->number($xpath, $tr, 'col-PG'),
                'points' => (int) $this->text($xpath, $tr, './/td['.$this->hasClass('bod').']'),
            ];
        }

        return $rows;
    }

    /** `div.game-row`: date/time column, `.team-host` / `.team-guest`, `.res-host` / `.res-guest`. */
    private function matches(DOMXPath $xpath, int $round): array
    {
        $rows = [];

        foreach ($xpath->query('//*['.$this->hasClass('game-row').']') as $row) {
            $home = $this->text($xpath, $row, './/*['.$this->hasClass('team-host').']');
            $away = $this->text($xpath, $row, './/*['.$this->hasClass('team-guest').']');

            if ($home === '' || $away === '') {
                continue;
            }

            $when = $this->text($xpath, $row, './/*['.$this->hasClass('border-r').']');
            $homeScore = $this->text($xpath, $row, './/*['.$this->hasClass('res-host').']');
            $awayScore = $this->text($xpath, $row, './/*['.$this->hasClass('res-guest').']');
            $played = ctype_digit($homeScore) && ctype_digit($awayScore);

            $rows[] = [
                'round' => $round,
                'date' => preg_match('/\d{2}\.\d{2}\.\d{4}/', $when, $d) ? $d[0] : null,
                'time' => preg_match('/\b(\d{1,2}:\d{2})\b/', $when, $t) ? $t[1] : null,
                'home' => $home,
                'away' => $away,
                'home_score' => $played ? (int) $homeScore : null,
                'away_score' => $played ? (int) $awayScore : null,
            ];
        }

        return $rows;
    }

    private function number(DOMXPath $xpath, DOMNode $tr, string $class): int
    {
        return (int) $this->text($xpath, $tr, './/td['.$this->hasClass($class).']');
    }

    private function text(DOMXPath $xpath, DOMNode $context, string $query): string
    {
        $node = $xpath->query($query, $context)->item(0);

        return $node ? trim(preg_replace('/[\s\x{A0}]+/u', ' ', $node->textContent)) : '';
    }

    /** Whole-word class match — `game-row` must not match `game-row-icons`. */
    private function hasClass(string $class): string
    {
        return 'contains(concat(" ", normalize-space(@class), " "), " '.$class.' ")';
    }
}
