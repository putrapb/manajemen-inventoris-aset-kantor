$ErrorActionPreference = 'Stop'

$mysqlBase = 'C:\Program Files\MySQL\MySQL Server 8.4'
$config = 'C:\TATA\mysql-my.ini'
$mysqld = Join-Path $mysqlBase 'bin\mysqld.exe'

if (-not (Test-Path -LiteralPath $mysqld)) {
    throw "MySQL tidak ditemukan di $mysqld. Install MySQL 8 terlebih dahulu."
}

if (-not (Test-Path -LiteralPath $config)) {
    throw "Konfigurasi MySQL tidak ditemukan di $config."
}

$alreadyRunning = Test-NetConnection -ComputerName 127.0.0.1 -Port 3306 -InformationLevel Quiet -WarningAction SilentlyContinue

if ($alreadyRunning) {
    Write-Host 'MySQL sudah berjalan di port 3306.'
    exit 0
}

Start-Process -FilePath $mysqld -ArgumentList @("--defaults-file=$config") -WindowStyle Hidden
Start-Sleep -Seconds 6

if (Test-NetConnection -ComputerName 127.0.0.1 -Port 3306 -InformationLevel Quiet -WarningAction SilentlyContinue) {
    Write-Host 'MySQL berhasil dijalankan di 127.0.0.1:3306.'
} else {
    throw "MySQL gagal dijalankan. Periksa log di C:\TATA\mysql-data\mysql-error.log."
}
