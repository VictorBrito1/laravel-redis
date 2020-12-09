<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /** @var \Illuminate\Redis\Connections\Connection $redis */
    private $redis;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $keys = $this->redis->keys('post:*');
        $posts = [];

        foreach ($keys as $key) {
            $stored = $this->redis->hgetall($key);

            $post = new Post();
            $post->id = $stored['id'];
            $post->titulo = $stored['titulo'];
            $post->corpo = $stored['corpo'];
            $post->usuario()->associate($stored['usuario']);

            $data_publicacao = \DateTime::createFromFormat('d/m/Y H:i:s', $stored['data_publicacao']);
            $post->data_publicacao = $data_publicacao;

            $posts[] = $post;
        }

        if ($posts) {
            error_log('Posts carregados do Redis.');
        } else {
            $posts = Post::all();

            if ($posts) {
                foreach ($posts as $post) {
                    $data['id'] = $post->id;
                    $data['titulo'] = $post->titulo;
                    $data['corpo'] = $post->corpo;
                    $data['usuario'] = $post->usuario->cpf;
                    $data['data_publicacao'] = $post->data_publicacao->format('d/m/Y H:i:s');
                    $this->insertOrUpdateRedisPost($data);
                }

                error_log('Posts inseridos no Redis.');
            }
        }

        return view('post.index', ['posts' => $posts]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $users = User::all();
        return view('post.create', ['users' => $users]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->only(['titulo', 'corpo', 'usuario']);

        $validator = Validator::make($data, [
            'titulo' => ['required', 'string', 'max:255'],
            'corpo' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('posts.create')
                ->withErrors($validator)
                ->withInput();
        }

        $post = new Post();
        $post->titulo = $data['titulo'];
        $post->corpo = $data['corpo'];
        $post->usuario()->associate($data['usuario']);
        $post->save();

        $now = new \DateTime();
        $data['id'] = $post->id;
        $data['data_publicacao'] = $now->format('d/m/Y H:i:s');
        $this->insertOrUpdateRedisPost($data, 'Post inserido com sucesso no REDIS!');

        return redirect()->route('posts.index');
    }

    /**
     * @param $cpf
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $stored = $this->redis->hgetall("post:{$id}");

        if ($stored) {
            $post = new Post();
            $post->id = $stored['id'];
            $post->titulo = $stored['titulo'];
            $post->corpo = $stored['corpo'];
            $post->usuario()->associate($stored['usuario']);

            $data_publicacao = \DateTime::createFromFormat('d/m/Y H:i:s', $stored['data_publicacao']);
            $post->data_publicacao = $data_publicacao;

            error_log("Post {$id} recuperado do Redis!");
        } else {
            $post = Post::find($id);
        }

        if ($post) {
            $users = User::all();
            return view('post.edit', ['post' => $post, 'users' => $users]);
        }

        return redirect()->route('posts.index');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if ($post) {
            $data = $request->only(['titulo', 'corpo', 'usuario']);

            $validator = Validator::make($data, [
                'titulo' => ['required', 'string', 'max:255'],
                'corpo' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('posts.edit', ['post' => $id])->withInput()->withErrors($validator);
            }


            $post->titulo = $data['titulo'];
            $post->corpo = $data['corpo'];
            $post->usuario()->associate($data['usuario']);
            $post->save();

            $stored = $this->redis->hgetall("post:{$id}");

            if ($stored) {
                $data['id'] = $id;
                $this->insertOrUpdateRedisPost($data, "Post {$id} encontrado e alterado no Redis!");
            }
        }

        return redirect()->route('posts.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post) {
            $post->delete();

            $redisPost = $this->redis->hgetall("post:{$id}");

            if ($redisPost) {
                $this->redis->del("post:{$id}");
                error_log("Post {$id} removido do Redis.");
            }
        }

        return redirect()->route('posts.index');
    }

    /**
     * @param $data
     * @param string $message
     */
    private function insertOrUpdateRedisPost($data, $message = '')
    {
        $this->redis->hmset("post:{$data['id']}", $data);

        if ($message) {
            error_log($message);
        }
    }
}
