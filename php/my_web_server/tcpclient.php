<?php

class TCPClient
{
    public function request()
    {
        # サーバーへリクエストを送信する
        echo "=== クライアントを起動します ===" . PHP_EOL;

        try {
            # socketを生成
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

            # サーバーと接続する
            echo "=== サーバーと接続します ===" . PHP_EOL;
            socket_connect($socket, '127.0.0.1', 80);
            echo "=== サーバーとの接続が完了しました ===" . PHP_EOL;

            # サーバーに送信するリクエストをファイルから取得する
            $request = file_get_contents('client_send.txt');

            # サーバーへリクエストを送信する
            socket_write($socket, $request, strlen($request));

            # サーバーから送られてきたデータを取得する
            $response = socket_read($socket, 1024);

            # レスポンスの内容を、ファイルに書き出す
            file_put_contents('client_recv.txt', $response, LOCK_EX);

            # 通信を終了させる
            socket_close($socket);
        } finally {
            echo "=== クライアントを停止します。 ===" . PHP_EOL;
        }
    }
}

$client = new TCPClient();
$client->request();
