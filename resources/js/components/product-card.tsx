import { Link } from '@inertiajs/react';
import { Product } from '@/types';

interface Props {
    product: Product;
}

export default function ProductCard({ product }: Props) {
    return (
        <Link
            href={`/products/${product.id}`}
            className="group overflow-hidden rounded-lg bg-white shadow-sm transition-shadow hover:shadow-md"
        >
            <div className="aspect-square overflow-hidden bg-gray-100">
                <img
                    src={product.image}
                    alt={product.name}
                    className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                />
            </div>

            <div className="p-4">
                <h3 className="mb-1 line-clamp-2 font-semibold text-gray-900">
                    {product.name}
                </h3>

                <p className="mb-2 text-sm text-gray-600">
                    by {product.merchant.name}
                </p>

                <div className="flex items-center justify-between">
                    <span className="text-xl font-bold text-blue-600">
                        ₦{product.price.toLocaleString()}
                    </span>

                    <span className="text-sm text-gray-500">
                        {product.stock} in stock
                    </span>
                </div>
            </div>
        </Link>
    );
}
