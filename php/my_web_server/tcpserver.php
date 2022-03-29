<?php

class TCPServer
{
    public function serve()
    {
        # サーバーを起動する
        echo "=== サーバーを起動します ===" . PHP_EOL;

        try {
            # socketを生成
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

            # socketをlocalhostのポート8080番に割り当てる
            socket_bind($socket, 'localhost', 8080);
            socket_listen($socket, 10);

            # 外部からの接続を待ち、接続があったらコネクションを確立する
            echo "=== クライアントからの接続を待ちます ===" . PHP_EOL;
            $msg_sock = socket_accept($socket);
            echo "=== クライアントとの接続が完了しました ===" . PHP_EOL;

            # クライアントから送られてきたデータを取得する
            $request = socket_read($msg_sock, 1024);

            # クライアントから送られてきたデータをファイルに書き出す
            file_put_contents('server_recv.txt', $request, LOCK_EX);

            # クライアントへ送信するレスポンスデータをファイルから取得する
            $response = file_get_contents('server_send.txt');

            # クライアントへレスポンスを送信する
            socket_send($msg_sock, $response, strlen($response), MSG_EOF);

            # 通信を終了させる
            socket_close($msg_sock);
            socket_close($socket);
        } finally {
            echo "=== サーバーを停止します。 ===" . PHP_EOL;
        }
    }
}

$server = new TCPServer();
$server->serve();
