                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    
                    <div class="row">
                        <div class="col-lg-6">

                            <!-- Jika eror -->
                            <?= form_error("menu", '<div class="alert alert-danger" role="alert">','</div'); ?>

                            <!-- Jika success -->
                            <?= $this->session->flashdata("messege"); ?>

                            <a href="" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newRoleModal">Add New Role</a>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <!-- Looping Menu -->

                                    <?php foreach($rol as $r) : ?>
                                    <tr>
                                        <th scope="row"><?= $i ?></th>
                                        <td><?= $r["role"] ?></td>
                                        <td>
                                            <a href="<?= base_url("admin/rolesccess/") . $r["id"] ; ?>" class="badge rounded-pill text-bg-warning">access</a>
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
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newRoleModalLabel">Add New Role</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url("admin/role") ?>" method="post">
          <div class="modal-body">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput role" name="role" placeholder="Role name">
                <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
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