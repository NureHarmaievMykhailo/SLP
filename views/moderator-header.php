<link href="../public/moderator-header.css" type="text/css" rel="stylesheet" />
<nav class="mod_navbar">
    <div class="greeter noselect"><p>Welcome, <?php echo $_SESSION['userData']['email']; ?>!</p></div>
    <ul class="mod_nav_list">
        <li class="mod_nav_item noselect"><a href="moderator-home">Home</a></li>
        <li class="mod_nav_item noselect"><a href="material_control_panel">Material overview</a></li>
        <li class="mod_nav_item noselect"><a href="teacher_control_panel">Teacher overview</a></li>
        <li class="mod_nav_item noselect"><a href="material_edit">Add new material</a></li>
        <li class="mod_nav_item noselect"><a href="teacher_edit">Add new teacher</a></li>
    </ul>
</nav>