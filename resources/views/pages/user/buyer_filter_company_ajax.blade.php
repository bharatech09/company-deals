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
                            <li>Have GST?: {{ $company['have_gst'] ?? 'N/A' }}</li>
                            <li>No. of Directors: {{ $company['no_of_directors'] }}</li>
                            <li>No. of Promoters: {{ $company['no_of_promoters'] }}</li>
                            <li>Activity Code: {{ $company['activity_code'] ?? 'N/A' }}</li>
                            <li>Authorised Capital:
                                ₹{{ number_format($company['authorized_capital'] ?? $company['authorised_capital_amount']) }}
                                {{ $company['authorized_capital_unit'] ?? 'Rupees' }}</li>
                            <li>Paid-up Capital:
                                ₹{{ number_format($company['paid_up_capital'] ?? $company['paidup_capital_amount']) }}
                                {{ $company['paid_up_capital_unit'] ?? 'Rupees' }}</li>
                            <li>Demat Shareholding: {{ $company['demat_shareholding'] }}%</li>
                            <li>Physical Shareholding: {{ $company['physical_shareholding'] }}%</li>
                            <li>Promoters Shareholding: {{ $company['promoters_holding'] ?? 'N/A' }}%</li>
                            <li>Transferable Shareholding: {{ $company['transferable_holding'] ?? 'N/A' }}%</li>
                            <li>Public Shareholding: {{ $company['public_holding'] ?? 'N/A' }}%</li>
                            <li>Current Market Price: ₹{{ $company['current_market_price'] ?? 'N/A' }}</li>
                            <li>52 Weeks High: ₹{{ $company['high_52_weeks'] ?? 'N/A' }}</li>
                            <li>52 Weeks Low: ₹{{ $company['low_52_weeks'] ?? 'N/A' }}</li>
                            <li>Market Capitalization: ₹{{ number_format($company['market_capitalization']) }}
                                {{ $company['market_capitalization_unit'] }}</li>
                            <li>Trading Conditions: {{ $company['trading_conditions'] ?? 'N/A' }}</li> 
                            <li>Acquisition Method: {{ $company['acquisition_method'] ?? 'N/A' }}</li>
                             <li>Face Value: ₹{{ $company['face_value'] ?? 'N/A' }}</li>
                            <li>Type of NBFC: {{ $company['type_of_NBFC'] ?? 'N/A' }}</li>
                            <li>Size of NBFC: {{ $company['size_of_NBFC'] ?? 'N/A' }}</li>
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $turnoverYear = $company['turnover_year' . $i] ?? null;
                                    $turnoverAmount = $company['turnover' . $i] ?? null;
                                    $turnoverUnit = $company['turnover_unit' . $i] ?? null;
                                    $profitYear = $company['profit_year'.$i] ?? null;
                                    $profitAmount = $company['profit'.$i] ?? null;
                                    $profitUnit = $company['profit_unit' . $i] ?? null;
                                @endphp
                                @if($turnoverYear || $turnoverAmount)
                                    <li>
                                        Turnover ({{ $turnoverYear ?? 'Year '.$i }}): 
                                        ₹{{ number_format($turnoverAmount) }} {{ $turnoverUnit }}
                                    </li>
                                @endif
                                @if($profitYear || $profitAmount)
                                    <li>
                                        Profit After Tax ({{ $profitYear ?? 'Year '.$i }}): 
                                        ₹{{ $profitAmount }} {{ $profitUnit }}
                                    </li>
                                @endif
                            @endfor
                            <li>Net Worth: ₹{{ number_format($company['net_worth']) }}
                                {{ $company['net_worth_unit'] }}</li>
                            <li>Reserve: ₹{{ number_format($company['reserve']) }} {{ $company['reserve_unit'] }}
                            </li>
                            <li>Secured Creditors: ₹{{ number_format($company['secured_creditors']) }}
                                {{ $company['secured_creditors_unit'] }}</li>
                            <li>Unsecured Creditors: ₹{{ number_format($company['unsecured_creditors']) }}
                                {{ $company['unsecured_creditors_unit'] }}</li>
                            <li>Land & Building: ₹{{ number_format($company['land_building']) }}
                                {{ $company['land_building_unit'] }}</li>
                            <li>Plant & Machinery: ₹{{ number_format($company['plant_machinery']) }}
                                {{ $company['plant_machinery_unit'] }}</li>
                            <li>Investment: ₹{{ number_format($company['investment']) }}
                                {{ $company['investment_unit'] }}</li>
                            <li>Debtors: ₹{{ number_format($company['debtors']) }} {{ $company['debtors_unit'] }}
                            </li>
                            <li>Cash & Bank: ₹{{ number_format($company['cash_bank']) }}
                                {{ $company['cash_bank_unit'] }}</li>
                            <li>ROC Status: {{ $company['roc_status'] }} ({{ $company['roc_year'] }})</li>
                            <li>Income Tax Status: {{ $company['income_tax_status'] }} ({{ $company['income_tax_year'] }})
                            </li>
                            <li>GST Status: {{ $company['gst_status'] }} ({{ $company['gst_year'] }})</li>
                            <li>RBI Status: {{ $company['rbi_status'] }} ({{ $company['rbi_year'] }})</li>
                            <li>FEMA Status: {{ $company['fema_status'] }} ({{ $company['fema_year'] }})</li>
                            <li>SEBI Status: {{ $company['sebi_status'] }} ({{ $company['sebi_year'] }})</li>
                            <li>Stock Exchange Status: {{ $company['stock_exchange_status'] }} ({{ $company['stock_exchange_year'] }})</li>
                           
                            <li>80G/12A Certificate: {{ $company['certicate_status'] ?? '' }} {{ $company['certicate_year'] ?? '' }}</li>

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