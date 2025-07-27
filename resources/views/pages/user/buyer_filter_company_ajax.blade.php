<input type="hidden" id="data_price_min" value="{{ $filerData['priceMin'] }}">
<input type="hidden" id="data_price_max" value="{{ $filerData['priceMax'] }}">

@foreach ($companyArr as $company)
    <div class="col-md-6">
        <div class="cards">
            <div class="card-featured">
                <article>
                    <ul class="feature-list">
                        <li>Name: {{$company['name']}} {{$company['name_prefix']}}</li>
                        <li>Type Of Entity: {{$company['type_of_entity']}}</li>
                        <li>ROC: {{$company['roc']}}</li>
                        <li>Year of Incorporation: {{$company['year_of_incorporation']}}</li>
                        <li>Industry: {{$company['industry']}}</li>
                        <li>Ask price: {{$company['ask_price']}} {{$company['ask_price_unit']}} per month</li>
                        <li>
                            <a href="{{ route('user.buyer.company.addtointerested', $company['id']) }}"
                                class="cta-primary interested_company" type="submit">
                                I am interested in this Company
                            </a>
                        </li>
                    </ul>

                    <div class="collapse" id="moreDetailss{{ $company['id'] }}">
                        <ul class="feature-list mt-2">
                            <li>Have GST?: {{ $company['have_gst'] ?? '' }}</li>
                            <li>No. of Directors: {{ $company['no_of_directors'] ?? '' }}</li>
                            <li>No. of Promoters: {{ $company['no_of_promoters'] ?? '' }}</li>
                            <li>Activity Code: {{ $company['activity_code'] ?? '' }}</li>
                            <li>Authorised Capital:
                                ₹{{ number_format($company['authorized_capital'] ?? $company['authorised_capital_amount'] ?? 0) }}
                                {{ $company['authorized_capital_unit'] ?? 'Rupees' }}
                            </li>
                            <li>Paid-up Capital:
                                ₹{{ number_format($company['paid_up_capital'] ?? $company['paidup_capital_amount'] ?? 0) }}
                                {{ $company['paid_up_capital_unit'] ?? 'Rupees' }}
                            </li>
                            <li>Demat Shareholding: {{ $company['demat_shareholding'] ?? '' }}%</li>

                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $turnoverYear = $company['turnover_year' . $i] ?? '';
                                    $turnoverAmount = $company['turnover' . $i] ?? '';
                                    $turnoverUnit = $company['turnover_unit' . $i] ?? '';
                                    $profitYear = $company['profit_year' . $i] ?? '';
                                    $profitAmount = $company['profit' . $i] ?? '';
                                    $profitUnit = $company['profit_unit' . $i] ?? '';
                                @endphp
                                @if($turnoverYear || $turnoverAmount)
                                    <li>
                                        Turnover ({{ $turnoverYear ?: 'Year ' . $i }}):
                                        ₹{{ number_format($turnoverAmount) }} {{ $turnoverUnit }}
                                    </li>
                                @endif
                                @if($profitYear || $profitAmount)
                                    <li>
                                        Profit After Tax ({{ $profitYear ?: 'Year ' . $i }}):
                                        ₹{{ $profitAmount }} {{ $profitUnit }}
                                    </li>
                                @endif
                            @endfor

                            <li>Net Worth: ₹{{ number_format($company['net_worth'] ?? 0) }}
                                {{ $company['net_worth_unit'] ?? '' }}</li>
                            <li>Reserve: ₹{{ number_format($company['reserve'] ?? 0) }} {{ $company['reserve_unit'] ?? '' }}
                            </li>
                            <li>Secured Creditors: ₹{{ number_format($company['secured_creditors'] ?? 0) }}
                                {{ $company['secured_creditors_unit'] ?? '' }}</li>
                            <li>Unsecured Creditors: ₹{{ number_format($company['unsecured_creditors'] ?? 0) }}
                                {{ $company['unsecured_creditors_unit'] ?? '' }}</li>
                            <li>Land & Building: ₹{{ number_format($company['land_building'] ?? 0) }}
                                {{ $company['land_building_unit'] ?? '' }}</li>
                            <li>Plant & Machinery: ₹{{ number_format($company['plant_machinery'] ?? 0) }}
                                {{ $company['plant_machinery_unit'] ?? '' }}</li>
                            <li>Investment: ₹{{ number_format($company['investment'] ?? 0) }}
                                {{ $company['investment_unit'] ?? '' }}</li>
                            <li>Debtors: ₹{{ number_format($company['debtors'] ?? 0) }} {{ $company['debtors_unit'] ?? '' }}
                            </li>
                            <li>Cash & Bank: ₹{{ number_format($company['cash_bank'] ?? 0) }}
                                {{ $company['cash_bank_unit'] ?? '' }}</li>

                            <li>ROC Status: {{ $company['roc_status'] ?? '' }} ({{ $company['roc_year'] ?? '' }})</li>
                            <li>Income Tax Status: {{ $company['income_tax_status'] ?? '' }}
                                ({{ $company['income_tax_year'] ?? '' }})</li>
                            <li>GST Status: {{ $company['gst_status'] ?? '' }} ({{ $company['gst_year'] ?? '' }})</li>
                            <li>RBI Status: {{ $company['rbi_status'] ?? '' }} ({{ $company['rbi_year'] ?? '' }})</li>
                            <li>FEMA Status: {{ $company['fema_status'] ?? '' }} ({{ $company['fema_year'] ?? '' }})</li>
                            <li>SEBI Status: {{ $company['sebi_status'] ?? '' }} ({{ $company['sebi_year'] ?? '' }})</li>
                            <li>Stock Exchange Status: {{ $company['stock_exchange_status'] ?? '' }}
                                {{ $company['stock_exchange_year'] ?? '' }}</li>
                            <li>80G/12A Certificate: {{ $company['ceritificate_status'] ?? '' }}
                                {{ $company['ceritificate_year'] ?? '' }}</li>
                        </ul>

                    </div>

                    <div class="text-center">
                        <span class="toggle-more-details2" data-bs-toggle="collapse"
                            data-bs-target="#moreDetailss{{ $company['id'] }}" aria-expanded="false"
                            aria-controls="moreDetailss{{ $company['id'] }}"
                            style="color: black; font-weight: 700; cursor: pointer;">
                            Show More
                        </span>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endforeach