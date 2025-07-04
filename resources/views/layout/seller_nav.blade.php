<div class="col-lg-4 col-xl-3">
    <nav class="dashboard-nav">
        <header>
            <h3>Menu</h3>
            <a href="javascript:void(0)" class="dashboard-nav-close navToggle2">
                <img src="{{ asset('images/cross-icon-w.png') }}" alt="cross-icon-white">
            </a>
        </header>
        <ul>
            <li>
                <a href="{{ route('user.seller.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('user.seller.companylist') }}">
                    Companies
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.propertylist') }}">
                    Properties
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.noctrademark') }}">
                    Trademarks
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.assignments') }}">
                    Assignments
                </a>
            </li>

            <li>
                <a href="{{ route('user.buyer.messaage') }}">
                    Message from Admin
                </a>
            </li>



            <li>
                <a href="{{ route('user.change-password') }}">
                    Profile
                </a>
            </li>
            <!-- <li>
                <a href="{{ route('user.seller.payment.history') }}">
                    Payment History
                </a>
            </li> -->
            <li>
                <a href="{{ route('user.logout') }}">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</div>
