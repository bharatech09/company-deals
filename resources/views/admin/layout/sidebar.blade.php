<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon mdi mdi-television"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
       <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.userlist') }}">
        <i class="menu-icon mdi mdi-account-multiple"></i>
        <span class="menu-title">Users</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.companylist') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">Companies</span>
      </a>
    </li>

   
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.propertylist') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">Properties</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.trademarklist') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">NOC Trademarks</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.assignmentlist') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">Assignments</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.homepage') }}">
        <i class="menu-icon mdi mdi-image-area"></i>
        <span class="menu-title">Home Page Banners</span>
      </a>
    </li>


   <li class="nav-item">
  <a class="nav-link" href="{{ route('admin.about', 'about') }}">
    <i class="menu-icon mdi mdi-information-outline"></i>
    <span class="menu-title">About Us Page</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.about', 'term') }}">
    <i class="menu-icon mdi mdi-file-document-box"></i>
    <span class="menu-title">Terms & Conditions</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.about', 'privacy') }}">
    <i class="menu-icon mdi mdi-lock-outline"></i>
    <span class="menu-title">Privacy Policy</span>
  </a>
</li>


    <li class="nav-item">
      <a class="nav-link" href="{{ route('pages.messages.list') }}">
        <i class="menu-icon mdi mdi-message-text-outline"></i>
        <span class="menu-title">Send Message</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.adminlist') }}">
        <i class="menu-icon mdi mdi-account-circle"></i>
        <span class="menu-title">Admins</span>
      </a>
    </li>
  
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.testimonial.list') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">Testimonials</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.announcement.list') }}">
        <i class="menu-icon mdi mdi-home-circle"></i>
        <span class="menu-title">Announcements</span>
      </a>
    </li>

  </ul>
</nav>