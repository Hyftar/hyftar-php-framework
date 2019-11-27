<?php

namespace App;

class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'AvecDuBeurre';
    const DB_USER = 'root';
    const DB_PASSWORD = 'password'; # DO NOT COMMIT YOUR DB PASSWORD

    # Randomly generated pepper used for encryption
    const PEPPER = '364aba675151243a1e105cee426271f843bd1bdd7ab822291fce8b8455d7c6e4';

    const LOG_TO_FILE = false;

    const SHOW_ERRORS = false;
}
