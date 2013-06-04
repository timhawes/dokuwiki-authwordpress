## Configuration ##

Add the following to conf/local.php:

    $conf['authtype'] = 'authwordpress';
    $conf['auth']['wordpress']['dsn'] = 'mysql:host=localhost;dbname=DATABASE';
    $conf['auth']['wordpress']['username'] = 'DATABASEUSERNAME';
    $conf['auth']['wordpress']['password'] = 'DATABASEPASSWORD';

