import { useForm } from '@inertiajs/react';
import { CartItem } from '@/types';
import { FormEventHandler } from 'react';

interface Props {
    item: CartItem;
}

export default function CartItemRow({ item }: Props) {
    const { delete: destroy } = useForm();
    const { put, data, setData } = useForm({
        quantity: item.quantity,
    });

    const updateQuantity = (newQuantity: number) => {
        setData('quantity', newQuantity);
        put(`/cart/${item.id}`, {
            preserveScroll: true,
        });
    };

    const removeItem: FormEventHandler = (e) => {
        e.preventDefault();
        if (confirm('Remove this item from cart?')) {
            destroy(`/cart/${item.id}`);
        }
    };

    return (
        <div className="flex items-center space-x-4 border-b py-4">
            <img
                src={item.product.image}
                alt={item.product.name}
                className="h-20 w-20 rounded object-cover"
            />

            <div className="flex-1">
                <h3 className="font-semibold">{item.product.name}</h3>
                <p className="text-sm text-gray-600">
                    ₦{item.price_at_add.toLocaleString()}
                </p>
            </div>

            <div className="flex items-center rounded border">
                <button
                    onClick={() =>
                        updateQuantity(Math.max(1, data.quantity - 1))
                    }
                    className="px-2 py-1 hover:bg-gray-100"
                >
                    -
                </button>
                <span className="px-4">{data.quantity}</span>
                <button
                    onClick={() =>
                        updateQuantity(
                            Math.min(item.product.stock, data.quantity + 1),
                        )
                    }
                    className="px-2 py-1 hover:bg-gray-100"
                >
                    +
                </button>
            </div>

            <div className="font-semibold">
                ₦{item.subtotal.toLocaleString()}
            </div>

            <button
                onClick={removeItem}
                className="text-red-600 hover:text-red-700"
            >
                Remove
            </button>
        </div>
    );
}
