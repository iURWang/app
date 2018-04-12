<?php

namespace Buff\controllers\user;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Query\Builder;
use Buff\classes\services\ResponseService;
use Buff\lib\data\StringEx;

class UserController
{
   protected $container;
   protected $table;
   private $responseService;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
       $this->table = $container->get('db')->table('user');
       $this->responseService = new ResponseService();
   }

   public function get(Request $req,  Response $res, $args = []) {

        $uid = $req->getQueryParam('uid',$default = '');
        $account = $req->getQueryParam('account',$default = '');
        if (empty($uid) && empty($account)) {
          $this->responseService->withFailure();
          $this->responseService->withErrorCode(5001);
        } else {
          $start = microtime(true);
          $user = $this->table->orWhere([['uid', '=', $uid],['account', '=', $account]])->get()->first();
          var_dump($this->table->where([['uid', '=', $uid],['account', '=', $account]],null,null,'or')->toSql());die;
          $expend = (microtime(true)-$start)*1000;
          if (empty($user)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(6001);
          } else {
            $userModel = new UserModel($user);
            if ($userModel->getPrivate() == true) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(7001);
            } else {
                $this->responseService->withSuccess();
                $this->responseService->withData($userModel);
                $this->responseService->withExpend($expend);
            }
          }
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function create(Request $req,  Response $res, $args = []) {

        
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('create');
   }

   public function auth(Request $req,  Response $res, $args = []) {

        
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('auth');
   }

   public function update(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('update');
   }

   public function delete(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('delete');
   }
}