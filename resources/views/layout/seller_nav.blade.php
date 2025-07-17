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
                 Add Companies
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.propertylist') }}">
                 Add Properties
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.noctrademark') }}">
                   Add Trademarks
                </a>
            </li>

            <li>
                <a href="{{ route('user.seller.assignments') }}">
                  Add Assignments
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
