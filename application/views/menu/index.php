

        

        

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

                            <a href="" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newMenuModal">Add New Menu</a>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <!-- Looping Menu -->

                                    <?php foreach($menu as $m) : ?>
                                    <tr>
                                        <th scope="row"><?= $i ?></th>
                                        <td><?= $m["menu"] ?></td>
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
<div class="modal fade" id="newMenuModal" tabindex="-1" aria-labelledby="newMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newMenuModalLabel">Add New Menu</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url("menu") ?>" method="post">
          <div class="modal-body">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput menu" name="menu" placeholder="Menu name">
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