<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa-solid fa-file-code"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Aplink Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Querry dari menu -->
             <?php

                $role_id = $this->session->userdata("role_id");
                $querryMenu = "SELECT `user_menu`.`id`, `menu`
                                    FROM `user_menu` JOIN `user_access_menu`
                                    ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                                    WHERE `user_access_menu`.`role_id` = $role_id
                                ORDER BY `user_access_menu`.`menu_id` ASC
                                ";

                $menu = $this->db->querryMenu->result_array();

             ?>

            <!-- Looping Menu -->
             <?php foreach($menu as $m) : ?>
            <div class="sidebar-heading">
                <?= $m["menu"]; ?>
            </div>

            <!-- Looping SubMenu sesuai Menu -->
             <?php
                $menuId = $m["id"];
                $querrySubMenu = "SELECT *
                                    FROM `user_sub_menu` JOIN `user_menu` 
                                    ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                                    WHERE `user_sub_menu`.`menu_id` = $menuId
                                    AND `user_sub_menu`.`is_active` = 1
                                ";

                $subMenu = $this->db->query($querySubMenu)->result_array();
             ?>

                <?php foreach($subMenu as $sm):?>
                <!-- Nav Item - Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url($sm["url"]); ?>">
                        <i class="<?= $sm["icon"]; ?>"></i>
                        <span><?= $sm["title"]; ?></span></a>
                </li>
                <?php endforeach?>

                <!-- Divider -->
            <hr class="sidebar-divider">

            <?php endforeach;?>

            
            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url("auth/logout") ?>">
                    <i class="fa-solid fa-fw fa-person-hiking"></i>
                    <span>Logout</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->