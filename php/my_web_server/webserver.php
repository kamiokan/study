<?php

class WebServer
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

            # レスポンスボディを生成
            $response_body = "<html><body><h1>It works!Feeling good.</h1></body></html>";

            # レスポンスラインを生成
            $response_line = "HTTP/1.1 200 OK\r\n";

            # 現在時刻を取得
            $objDateTime = new DateTime('NOW', new DateTimeZone('GMT'));
            $response_datetime = $objDateTime->format('D, d M Y H:i:s e');

            # レスポンスヘッダーを生成
            $response_header = "Date: ${response_datetime}\r\n";
            $response_header .= "Host: HenachokoServer/0.1\r\n";
            $response_header .= "Content-Length: " . strlen($response_body) . "\r\n";
            $response_header .= "Connection: Close\r\n";
            $response_header .= "Content-Type: text/html\r\n";

            # ヘッダーとボディを空行でくっつけ、レスポンス全体を生成する
            $response = $response_line . $response_header . "\r\n" . $response_body;

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

$server = new WebServer();
$server->serve();
