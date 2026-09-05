@props(['rows', 'compact' => false, 'dark' => false])

@php
    $cols = __('club.match.cols');
    $textMuted = $dark ? 'text-navy-300' : 'text-ink-400';
@endphp

@if ($rows->isEmpty())
    <p class="text-sm {{ $textMuted }}">{{ __('club.match.no_table') }}</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm {{ $dark ? 'text-navy-100' : 'text-ink-700' }}">
            <thead>
                <tr class="text-[10.5px] font-bold uppercase tracking-[0.14em] {{ $textMuted }}">
                    <th class="py-2 pr-2 text-left font-bold">{{ $cols['pos'] }}</th>
                    <th class="py-2 pr-2 text-left font-bold">{{ $cols['team'] }}</th>
                    <th class="py-2 px-2 text-center font-bold">{{ $cols['p'] }}</th>
                    @unless ($compact)
                        <th class="py-2 px-2 text-center font-bold">{{ $cols['w'] }}</th>
                        <th class="py-2 px-2 text-center font-bold">{{ $cols['d'] }}</th>
                        <th class="py-2 px-2 text-center font-bold">{{ $cols['l'] }}</th>
                    @endunless
                    <th class="py-2 px-2 text-center font-bold">{{ $cols['gd'] }}</th>
                    <th class="py-2 pl-2 text-right font-bold">{{ $cols['pts'] }}</th>
                    @unless ($compact)
                        <th class="hidden py-2 pl-4 text-right font-bold sm:table-cell">{{ $cols['form'] }}</th>
                    @endunless
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="border-t {{ $dark ? 'border-white/10' : 'border-surface-300' }} {{ $row->is_club ? ($dark ? 'bg-white/10 font-bold text-white' : 'bg-gold-100 font-bold text-navy-900') : '' }}">
                        <td class="py-2.5 pr-2 font-display text-base font-bold">
                            <span class="{{ $row->is_club ? 'text-red-500' : '' }}">{{ $row->position }}</span>
                        </td>
                        <td class="py-2.5 pr-2 whitespace-nowrap">{{ $row->team }}</td>
                        <td class="py-2.5 px-2 text-center tabular-nums">{{ $row->played }}</td>
                        @unless ($compact)
                            <td class="py-2.5 px-2 text-center tabular-nums">{{ $row->won }}</td>
                            <td class="py-2.5 px-2 text-center tabular-nums">{{ $row->drawn }}</td>
                            <td class="py-2.5 px-2 text-center tabular-nums">{{ $row->lost }}</td>
                        @endunless
                        <td class="py-2.5 px-2 text-center tabular-nums">{{ $row->goal_difference > 0 ? '+' : '' }}{{ $row->goal_difference }}</td>
                        <td class="py-2.5 pl-2 text-right font-display text-lg font-black tabular-nums">{{ $row->points }}</td>
                        @unless ($compact)
                            <td class="hidden py-2.5 pl-4 text-right sm:table-cell">
                                <span class="inline-flex gap-1">
                                    @foreach (str_split((string) $row->form) as $r)
                                        @if (in_array($r, ['W', 'D', 'L']))<span class="form-pill form-{{ $r }}">{{ $r }}</span>@endif
                                    @endforeach
                                </span>
                            </td>
                        @endunless
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
