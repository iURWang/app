<?php

namespace Buff\classes\services;

use Buff\classes\response\ResponseModelInterface;
use APP;

class ResponseService
{
	private $right = true;
    private $code = 0;
    private $msg = '';
    private $params = [];
    private $timestamp;
    private $time = '';
    private $content = [];

    public function withSuccess()
	{
		$this->right = true;

		return $this;
	}

	public function withFailure()
	{
		$this->right = false;

		return $this;
	}

	public function withCode($code,$params = [])
	{
		$this->code = $code;
		$this->params = $params;

		return $this;
	}

	public function withMsg($msg)
	{
		$this->msg = $msg;

		return $this;
	}

	public function withContent($content = [])
	{
		if (!is_array($content) && !$content instanceof ResponseModelInterface) {
			throw new \Exception('Malformed response content.');
		}
		
		if ($content instanceof ResponseModelInterface) {
			$content = $content->expose();
		}
		
		$this->content = $content;
		
		return $this;
	}

	public function withTime($time)
	{
		$this->time = (string) $time;
		
		return $this;
	}

	public function write()
	{
		return $this->getResponse();
	}

	public function getResponse()
	{
		$this->timestamp = time();
		$this->responseData['h']['r'] = $this->right;
		$this->responseData['h']['c'] = $this->code;
		$this->responseData['h']['m'] = $this->getMsg();
		$this->responseData['h']['s'] = $this->timestamp;
		$this->responseData['h']['t'] = $this->time;
		$this->responseData['c'] = $this->content;

		return json_encode($this->responseData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

	public function getMsg(): string
	{
		$msgtpl = $this->msg;
        if (empty($msgtpl)) {
        	$msgtpl = APP::$base->config->get($this->code,'error');
        }
        if (empty($msgtpl)) {
        	$msgtpl = '';
        }

        return vsprintf($msgtpl, $this->params);
	}
}