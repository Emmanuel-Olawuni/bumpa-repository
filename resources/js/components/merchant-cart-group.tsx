import { CartItem, Merchant } from '@/types';
import CartItemRow from './cart-items-row';

interface Props {
    merchant: Merchant;
    items: CartItem[];
    subtotal: number;
}

export default function MerchantCartGroup({
    merchant,
    items,
    subtotal,
}: Props) {
    return (
        <div className="mb-6 rounded-lg bg-white p-6 shadow-sm">
            <div className="mb-4 flex items-center border-b pb-4">
                <img
                    src={merchant.logo}
                    alt={merchant.name}
                    className="mr-3 h-12 w-12 rounded-full"
                />
                <div>
                    <h2 className="text-lg font-bold">{merchant.name}</h2>
                    <p className="text-sm text-gray-600">
                        {items.length} item(s)
                    </p>
                </div>
            </div>

            {items.map((item) => (
                <CartItemRow key={item.id} item={item} />
            ))}

            <div className="mt-4 flex justify-end border-t pt-4">
                <div className="text-right">
                    <p className="text-sm text-gray-600">Subtotal</p>
                    <p className="text-xl font-bold">
                        ₦{subtotal.toLocaleString()}
                    </p>
                </div>
            </div>
        </div>
    );
}
