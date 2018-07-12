<h1><?php echo $data['title']; ?></h1>
<p><?php echo $data['description']; ?></p>

<h2 class="nav-tab-wrapper">
    <a href="?page=gf_frontend-help" class="nav-tab <?php echo $active_tab == 'submenu1' ? 'nav-tab-active' : ''; ?>">Help</a>
    <a href="?page=gf_frontend-settings" class="nav-tab <?php echo $active_tab == 'home' ? 'nav-tab-active' : ''; ?>">Settings</a>
</h2>
