

        

        

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <div class="row">
                        <div class="col-lg-6">

                            <a href="" class="btn btn-primary mb-3">Add New Menu</a>

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

            