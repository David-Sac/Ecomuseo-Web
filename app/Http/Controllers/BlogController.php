<?php

namespace App\Http\Controllers;

use App\Models\Components;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\View\View;
use Parsedown;
use Illuminate\Support\Facades\Auth;


//use Parsedown;             // arriba en tu controlador
use Illuminate\Support\Str; // si quieres usar Str::markdown()

class BlogController extends Controller
{

    public function __construct()
    {
        //    $this->middleware('auth');
        $this->middleware('auth')->except(['publicShow', 'publicIndex']);

       $this->middleware('permission:create-blog|edit-blog|delete-blog', ['only' => ['index','show']]);
       $this->middleware('permission:create-blog', ['only' => ['create','store']]);
       $this->middleware('permission:edit-blog', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-blog', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Inicializa la consulta de blogs incluyendo las relaciones necesarias
        $query = Blog::with(['author', 'components']);

        // Si el usuario es un voluntario, filtrar para mostrar solo sus propios blogs
        if (Auth::user()->hasRole('Volunteer')) {
            $query->where('author_id', Auth::id());
        }
        // No es necesario aplicar ningún filtro para admin y superAdmin, ellos pueden ver todos los blogs

        $blogs = $query->latest()->paginate(10); // Ajusta la paginación como prefieras

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $components = Components::all(); // Obtener todos los componentes

        return view('blogs.create', compact('components'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();

        // Si nos llegó un fichero, lo guardamos en public/storage/images/blogs y guardamos la ruta
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request
                ->file('image_path')
                ->store('images/blogs', 'public');
        }

        // Creamos el blog con el author_id
        $blog = Blog::create(array_merge(
            $data,
            ['author_id' => auth()->id()]
        ));

        // Relacionamos componentes si vienen
        $blog->components()->sync($data['components'] ?? []);

        return redirect()->route('blogs.index')
                         ->with('success', 'Blog creado con éxito.');
    }


    public function approve($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update(['status' => 'approved']);
        return back()->with('success', 'Blog approved successfully.');
    }


    public function decline($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update(['status' => 'rejected']);
        return back()->with('success', 'Blog declined successfully.');
    }

    public function publicIndex()
    {
        $blogs = Blog::with('components')
                    ->where('status', 'approved')
                    ->get();

        foreach ($blogs as $blog) {
            // 1) Si tiene su propia imagen_path, úsala:
            if ($blog->image_path) {
                // guardamos la ruta relativa a public/
                $blog->displayImage = 'storage/' . $blog->image_path; // <<<—
            }
            // 2) Sino, si hay componente con imagen, tomamos uno aleatorio:
            elseif ($blog->components->isNotEmpty() 
                && $blog->components->first()->rutaImagenComponente) {
                $random = $blog->components
                            ->whereNotNull('rutaImagenComponente')
                            ->random();
                $blog->displayImage = $random->rutaImagenComponente;
            }
            // 3) Y de último, un placeholder genérico:
            else {
                $blog->displayImage = 'images/default-blog.jpg';
            }
        }

        return view('blogs.public_index', compact('blogs'));
    }
    public function publicShow($id)
    {
        $blog = Blog::with('author','components')->findOrFail($id);

        // ——— Aquí
        if ($blog->image_path) {
            // guardamos la ruta relativa a public/
            $blog->displayImage = 'storage/' . $blog->image_path;
        }
        elseif ($blog->components->isNotEmpty() && $blog->components->first()->rutaImagenComponente) {
            $random = $blog->components
                        ->whereNotNull('rutaImagenComponente')
                        ->random();
            $blog->displayImage = $random->rutaImagenComponente;
        } else {
            $blog->displayImage = 'images/default-blog.jpg';
        }
        // ———

        // parsear Markdown
        $parsedown = new Parsedown();
        $blog->content = $parsedown->text($blog->content);

        return view('blogs.publicShow', compact('blog'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blog = Blog::with(['author', 'components'])->findOrFail($id);

        // Si deseas pasar también los nombres de los componentes asociados o cualquier otra información adicional, puedes hacerlo aquí.
        // Por ejemplo, si quisieras pasar una lista de todos los componentes para mostrarlos en algún tipo de widget o lista en la vista, podrías cargarlos aquí.

        return view('blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Método para mostrar el formulario de edición
    public function edit(Blog $blog)
    {
        $components = Components::all(); // Obtiene todos los componentes para la selección
        return view('blogs.edit', compact('blog', 'components'));
    }

    // Método para actualizar el blog
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $data = $request->validated();

        if ($request->hasFile('image_path')) {
            // Eliminamos la imagen vieja (si existía)
            if ($blog->image_path) {
                Storage::disk('public')->delete($blog->image_path);
            }
            // Subimos la nueva
            $data['image_path'] = $request
                ->file('image_path')
                ->store('images/blogs', 'public');
        }

        // Actualizamos datos y relaciones
        $blog->update($data);
        $blog->components()->sync($data['components'] ?? []);

        return redirect()->route('blogs.index')
                         ->with('success', 'Blog actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')
                ->withSuccess('Blog is deleted successfully.');
    }
}
