import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import MerchantCartGroup from '@/components/merchant-cart-group';
import { CartItem, Merchant } from '@/types';

interface MerchantGroup {
    merchant: Merchant;
    items: CartItem[];
    subtotal: number;
}

interface Props {
    itemsByMerchant: MerchantGroup[];
    total: number;
}

export default function CartIndex({ itemsByMerchant, total }: Props) {
    const isEmpty = itemsByMerchant.length === 0;

    return (
        <MainLayout>
            <Head title="Shopping Cart" />

            <div className="container mx-auto px-4 py-8">
                <h1 className="mb-8 text-3xl font-bold text-black">
                    Shopping Cart
                </h1>

                {isEmpty ? (
                    <div className="py-12 text-center">
                        <p className="mb-4 text-gray-600">Your cart is empty</p>
                        <Link
                            href="/"
                            className="text-blue-600 hover:underline"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                ) : (
                    <div className="grid gap-8 lg:grid-cols-3">
                        <div className="lg:col-span-2">
                            {itemsByMerchant.map((group) => (
                                <MerchantCartGroup
                                    key={group.merchant.id}
                                    merchant={group.merchant}
                                    items={group.items}
                                    subtotal={group.subtotal}
                                />
                            ))}
                        </div>

                        <div className="lg:col-span-1">
                            <div className="sticky top-4 rounded-lg bg-white p-6 shadow-sm">
                                <h2 className="mb-4 text-xl font-bold text-black">
                                    Order Summary
                                </h2>

                                <div className="mb-4 space-y-2">
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">
                                            Subtotal
                                        </span>
                                        <span className="text-black">
                                            ₦{total.toLocaleString()}
                                        </span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">
                                            Shipping
                                        </span>
                                        <span className="text-black">
                                            Calculated at checkout
                                        </span>
                                    </div>
                                </div>

                                <div className="mb-6 border-t pt-4">
                                    <div className="flex justify-between text-xl font-bold">
                                        <span className="text-black">
                                            Total
                                        </span>
                                        <span className="text-black">
                                            ₦{total.toLocaleString()}
                                        </span>
                                    </div>
                                </div>

                                <Link
                                    href="/checkout"
                                    className="block w-full rounded-lg bg-blue-600 py-3 text-center font-semibold text-white hover:bg-blue-700"
                                >
                                    Proceed to Checkout
                                </Link>

                                <Link
                                    href="/"
                                    className="mt-4 block text-center text-blue-600 hover:underline"
                                >
                                    Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </MainLayout>
    );
}
