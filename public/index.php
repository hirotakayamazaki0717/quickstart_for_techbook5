<?php
require_once '../app.php';
require_once join_paths([MODELS_ROOT, 'Task.php']);

$tasks   = Task::all();
$session = session('tasks');
$errors  = $session->flash('errors', []);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Laravel Quickstart - Basic</title>

        <!-- Fonts -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container">
                <!-- navbar  -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                      Task List
                    </a>
                </div>
            </div>
        </nav>
        <!-- @yield('content') -->
        <div class="container">
            <div class="col-sm-offset-2 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        New Task
                    </div>

                    <div class="panel-body">
                    <!-- Display Validation Errors -->
                    <!-- @include('common.errors') -->
                    <!-- Form Error List -->
                    <?php if ($errors) : ?>
                    <div class="alert alert-danger">
                        <strong>エラーがありますよ！!</strong>
                        <br><br>
                        <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- New Task Form -->
                    <form action="/task-store.php" method="POST" class="form-horizontal">
                        <!-- {{ csrf_field() }} -->
                        <?php csrf_field($session); ?>
                        <!-- Task Name  -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Task</label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control">
                            </div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- TODO: Current Tasks -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Tasks
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Task</th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody>
                            <?php foreach ($tasks as $task) : ?>
                                <tr>
                                    <td class="table-text"><div>
                                        <?php echo h($task['name']); ?>
                                    </div></td>
                                    <!-- Delete Button -->
                                    <td>
                                        <form action="/task-delete.php?id=<?php echo h($task['id']); ?>" method="POST">
                                            <!-- {{ csrf_field() }} -->
                                            <?php csrf_field($session); ?>
                                            <!-- {{ method_field('DELETE') }} -->
                                            <button type="submit" class="btn btn-danger pull-right">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScripts -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </body>
</html>