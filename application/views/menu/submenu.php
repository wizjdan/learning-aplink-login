

        

        

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    
                    <div class="row">
                        <div class="col-lg">
                            <?php if(validation_errors()) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= validation_errors(); ?>
                                </div>
                            <?php endif?>

                            <!-- Jika success -->
                            <?= $this->session->flashdata("messege"); ?>

                            <a href="" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newsSubMenuModal">Add New Submenu</a>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">URL</th>
                                        <th scope="col">Icon</th>
                                        <th scope="col">Active</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <!-- Looping Menu -->

                                    <?php foreach($subMenu as $sm) : ?>
                                    <tr>
                                        <th scope="row"><?= $i ?></th>
                                        <td><?= $sm["title"] ?></td>
                                        <td><?= $sm["menu_id"] ?></td>
                                        <td><?= $sm["url"] ?></td>
                                        <td><?= $sm["icon"] ?></td>
                                        <td><?= $sm["is_active"] ?></td>
                                        <td>
                                            <a href="" class="badge rounded-pill text-bg-success">edit</a>
                                            <a href="" class="badge rounded-pill text-bg-danger">delete</a>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                    <?php endforeach ; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newSubMenuModalLabel">Add New Sub Menu</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url("menu/submenu") ?>" method="post">
          <div class="modal-body">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput title" name="title" placeholder="Submenu title">
                <label for="floatingInput">Sub Menu Title</label>
                </div>
                <div class="from-group">
                    <select name="menu_id" id="menu_id" class="form-control">
                    
                    <option value="">Select Menu</option>

                    <?php foreach($menu as $m) : ?>
                        <option value="<?= $m["id"]; ?>"><?= $m["imenud"]; ?></option>
                    <?php endforeach ; ?>
                    
                    </select>
                </div>
                <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput url" name="url" placeholder="Submenu URL">
                <label for="floatingInput">Submenu URL</label>
                </div>
                <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword icon" name="icon" placeholder="icon">
                <label for="floatingPassword">Submenu Icon</label>
                <div class="form-group">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Active?
                    </label>
                </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
    </div>
  </div>
</div>