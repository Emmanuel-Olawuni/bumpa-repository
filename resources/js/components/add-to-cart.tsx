import { useForm } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

interface Props {
    productId: number;
    stock: number;
}

export default function AddToCartButton({ productId, stock }: Props) {
    const [quantity, setQuantity] = useState(1);

    const { post, processing } = useForm({
        product_id: productId,
        quantity: quantity,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post('/cart/add');
    };

    return (
        <form onSubmit={submit} className="flex items-center space-x-4">
            <div className="flex items-center rounded-lg border">
                <button
                    type="button"
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="px-3 py-2 hover:bg-gray-100"
                >
                    -
                </button>
                <span className="border-x px-4 py-2">{quantity}</span>
                <button
                    type="button"
                    onClick={() => setQuantity(Math.min(stock, quantity + 1))}
                    className="px-3 py-2 hover:bg-gray-100"
                >
                    +
                </button>
            </div>

            <button
                type="submit"
                disabled={processing || stock === 0}
                className="flex-1 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-400"
            >
                {stock === 0
                    ? 'Out of Stock'
                    : processing
                      ? 'Adding...'
                      : 'Add to Cart'}
            </button>
        </form>
    );
}
