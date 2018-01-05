<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Ws\Chat;

class ChatServe extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'chat:serve';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Start the chat WS server';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            $port = $this->argument('port');
            $ip = $this->argument('ip');
	    echo 'WS listen to ' . $ip .':' . $port . "\n";
            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new Chat()
                    )
                )
                , $port, $ip
            );
            $server->run();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
                    ['port', InputArgument::OPTIONAL, 'WebSocket Port', 8080],
                    ['ip', InputArgument::OPTIONAL, 'WebSocket ip', '0.0.0.0'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [

		];
	}

}
