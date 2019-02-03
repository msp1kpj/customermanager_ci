<nav class="navbar navbar-expand-md navbar-dark bg-dark" role="banner">
      <?php echo anchor('main', '<h1 class="logo fontZrnic">Homeplace Mechanical</h1>', array("class"=>"navbar-brand mr-0 mr-md-2"));?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <?php if(isset($currentUser) && $currentUser->pkid ) { ?>
      <div class="collapse navbar-collapse">
          <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
            <li class="nav-item"><?php echo anchor('report', 'Reports', array("class"=>"nav-link"));?></li>
            <li class="nav-item"><?php echo anchor('users', 'Security', array("class"=>"nav-link"));?></li>
            <li class="nav-item dropdown">
                    <a href="##" class="nav-link dropdown-toggle" data-toggle="dropdown">Hi, <?php echo $currentUser->getName();?> <b class="caret"></b></a>
                    <div class="dropdown-menu dropdown-menu-right text-right">
                        <?php echo anchor('security/logout', '<span class="icon-off"></span> Sign Out', array("class"=>"dropdown-item"));?>
                    </div>
                </li>
        </ul>
    </div>
      <?php } ?>
</nav>