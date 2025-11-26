<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class productController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $data = [
                'message' => 'No se encontraron productos',
                'status' => 200
            ];
            return response()->json($data, 404);
        }

        $data = [
            'products' => $products,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => [
                'required','string','max:20',
                Rule::unique('products','sku')
            ],
            'name'=>'required|string|max:100',
            'description'=>'required|string|max:255',
            // 'description'=>'required|in:Deportivos,Casuales,Formales,Botas',
            'price'=>'required|numeric',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product = Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Producto creado exitosamente',
            'product' => $product,
            'status' => 201
        ];
        return response()->json($data, 201);

    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'product' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $product->delete();

        $data = [
            'message' => 'Producto eliminado exitosamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'sku' => [
                'required','string','max:20',
                Rule::unique('products','sku')->ignore($id)
            ],
            'name'=>'required|string|max:100',
            'description'=>'required|string|max:255',
            'price'=>'required|numeric',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product->sku = $request->sku;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        
        $product->save();

        $data = [
            'message' => 'Producto actualizado exitosamente',
            'product' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'sku' => [
                'sometimes','string','max:20',
                Rule::unique('products','sku')->ignore($id)
            ],
            'name'=>'sometimes|string|max:100',
            'description'=>'sometimes|string|max:255',
            'price'=>'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('sku')) {
            $product->sku = $request->sku;
        }
        if ($request->has('name')) {
            $product->name = $request->name;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }

        $product->save();

        $data = [
            'message' => 'Producto actualizado exitosamente',
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
