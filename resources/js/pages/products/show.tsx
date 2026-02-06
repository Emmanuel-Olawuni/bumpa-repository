import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import AddToCartButton from '@/components/add-to-cart';
import { Product } from '@/types';

interface Props {
    product: Product;
}

export default function ProductShow({ product }: Props) {
    return (
        <MainLayout>
            <Head title={product.name} />

            <div className="container mx-auto px-4 py-8">
                <Link
                    href="/"
                    className="mb-4 inline-block text-blue-600 hover:underline"
                >
                    ← Back to Products
                </Link>

                <div className="grid gap-8 md:grid-cols-2">
                    {/* Product Image */}
                    <div className="aspect-square overflow-hidden rounded-lg bg-gray-100">
                        <img
                            src={product.image}
                            alt={product.name}
                            className="h-full w-full object-cover"
                        />
                    </div>

                    {/* Product Info */}
                    <div>
                        <div className="mb-4">
                            <Link
                                href={`/merchants/${product.merchant.slug}`}
                                className="text-gray-600 hover:text-blue-600"
                            >
                                {product.merchant.name}
                            </Link>
                        </div>

                        <h1 className="mb-4 text-3xl font-bold text-gray-900">
                            {product.name}
                        </h1>

                        <p className="mb-6 text-gray-600">
                            {product.description}
                        </p>

                        <div className="mb-6">
                            <span className="text-3xl font-bold text-blue-600">
                                ₦{product.price.toLocaleString()}
                            </span>
                        </div>

                        <div className="mb-6">
                            <span className="text-sm text-gray-600">
                                {product.stock} items in stock
                            </span>
                        </div>

                        <AddToCartButton
                            productId={product.id}
                            stock={product.stock}
                        />

                        <div className="mt-8 border-t pt-6">
                            <h3 className="mb-2 font-semibold">
                                Product Details
                            </h3>
                            <ul className="space-y-2 text-sm text-gray-600">
                                <li>Category: {product.category}</li>
                                <li>Sold by: {product.merchant.name}</li>
                                <li>Contact: {product.merchant.phone}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
