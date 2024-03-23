<?php
    /*
     * EDIT and RENAME connect.php
     * */

    class BDconnect
    {
        private const host = "";//localhost
        private const user = '';//
        private const pass = '';//
        private const db_name ='';//
        public bool $status = false;
        public string $err = '';

        public function connect()
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $this->status = true;
                return mysqli_connect(self::host, self::user, self::pass, self::db_name);
            } catch (mysqli_sql_exception $e) {
                $this->err = $e->getMessage();
                $this->status = false;
                return null;
            }
        }

    }