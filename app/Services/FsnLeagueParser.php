<?php

namespace App\Services;

use App\Support\Cyrillic;
use DOMDocument;
use DOMNode;
use DOMXPath;

/**
 * Parses a league page of Fudbalski savez Niša (fsn.org.rs/druga-niska-liga …):
 * the standings table and the fixture/result rows of every round on the page.
 *
 * The markup is table soup with duplicated ids, so everything is matched by
 * attribute via XPath rather than by document structure. Team names are
 * returned transliterated to Latin.
 */
class FsnLeagueParser
{
    private const BYE = 'слободан';

    /**
     * @return array{
     *     standings: list<array{position:int,team:string,played:int,won:int,drawn:int,lost:int,goals_for:int,goals_against:int,points:int}>,
     *     matches: list<array{round:int,date:?string,time:?string,home:string,away:string,home_score:?int,away_score:?int}>
     * }
     */
    public function parse(string $html): array
    {
        $xpath = $this->xpath($html);

        return [
            'standings' => $this->standings($xpath),
            'matches' => $this->matches($xpath),
        ];
    }

    private function xpath(string $html): DOMXPath
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // the FSN markup is not well-formed; parser warnings are expected noise
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        return new DOMXPath($doc);
    }

    /** One `table#tabelasredina` per team: Tim, UT, PB, NR, IZ, GD, GP, GR, BOD. */
    private function standings(DOMXPath $xpath): array
    {
        $rows = [];

        foreach ($xpath->query('//table[@id="tabelasredina"]//tr') as $tr) {
            $cells = $this->cells($xpath, $tr);

            if (count($cells) < 9 || $cells[0] === '') {
                continue;
            }

            $rows[] = [
                'position' => count($rows) + 1,
                'team' => Cyrillic::toLatin($cells[0]),
                'played' => (int) $cells[1],
                'won' => (int) $cells[2],
                'drawn' => (int) $cells[3],
                'lost' => (int) $cells[4],
                'goals_for' => (int) $cells[5],
                'goals_against' => (int) $cells[6],
                'points' => (int) $cells[8],
            ];
        }

        return $rows;
    }

    /** Fixture rows `tr#rez` / `tr#rez1`: datum, vreme, domaćin, rezultat, gost. */
    private function matches(DOMXPath $xpath): array
    {
        $rows = [];

        foreach ($xpath->query('//tr[@id="rez" or @id="rez1"]') as $tr) {
            $cells = $this->cells($xpath, $tr);

            if (count($cells) < 5) {
                continue;
            }

            [$date, $time, $home, $result, $away] = $cells;

            if ($home === '' || $away === '' || in_array(self::BYE, [$home, $away], true)) {
                continue;
            }

            $round = $this->round($xpath, $tr);

            if ($round === null) {
                continue;
            }

            preg_match('/(\d+)\s*:\s*(\d+)/', $result, $score);

            $rows[] = [
                'round' => $round,
                'date' => preg_match('/\d{2}\.\d{2}\.\d{4}/', $date, $d) ? $d[0] : null,
                'time' => preg_match('/\d{1,2}:\d{2}/', $time, $t) ? $t[0] : null,
                'home' => Cyrillic::toLatin($home),
                'away' => Cyrillic::toLatin($away),
                'home_score' => isset($score[1]) ? (int) $score[1] : null,
                'away_score' => isset($score[2]) ? (int) $score[2] : null,
            ];
        }

        return $rows;
    }

    /** The nearest preceding "Коло бр: N" cell tells which round a fixture row belongs to. */
    private function round(DOMXPath $xpath, DOMNode $tr): ?int
    {
        $label = $xpath->query('preceding::td[starts-with(normalize-space(.), "Коло бр")][1]', $tr)->item(0);

        return $label && preg_match('/(\d+)/', $label->textContent, $m) ? (int) $m[1] : null;
    }

    /** @return list<string> */
    private function cells(DOMXPath $xpath, DOMNode $tr): array
    {
        $cells = [];

        foreach ($xpath->query('./td', $tr) as $td) {
            $cells[] = $this->clean($td->textContent);
        }

        return $cells;
    }

    private function clean(string $text): string
    {
        return trim(preg_replace('/[\s\x{A0}]+/u', ' ', $text), ' ,');
    }
}
