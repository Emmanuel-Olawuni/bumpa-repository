import { Head, useForm } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import { CartItem, Merchant } from '@/types';
import { FormEventHandler } from 'react';

interface MerchantGroup {
    merchant: Merchant;
    items: CartItem[];
    subtotal: number;
}

interface Props {
    itemsByMerchant: MerchantGroup[];
    subtotal: number;
    shippingFee: number;
    total: number;
    user?: {
        name: string;
        email: string;
    };
}

export default function CheckoutIndex({
    itemsByMerchant,
    subtotal,
    shippingFee,
    total,
    user,
}: Props) {
    const { data, setData, post, processing, errors } = useForm({
        customer_name: user?.name || '',
        customer_email: user?.email || '',
        customer_phone: '',
        shipping_address: '',
        city: '',
        state: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post('/checkout');
    };

    return (
        <MainLayout>
            <Head title="Checkout" />

            <div className="container mx-auto px-4 py-8">
                <h1 className="mb-8 text-3xl font-bold text-black">Checkout</h1>

                <div className="grid gap-8 lg:grid-cols-3">
                    {/* Checkout Form */}
                    <div className="lg:col-span-2">
                        <form
                            onSubmit={submit}
                            className="rounded-lg bg-white p-6 shadow-sm"
                        >
                            <h2 className="mb-6 text-xl font-bold text-black">
                                Shipping Information
                            </h2>

                            <div className="space-y-4">
                                <div>
                                    <label className="mb-2 block text-sm font-medium text-black">
                                        Full Name *
                                    </label>
                                    <input
                                        type="text"
                                        value={data.customer_name}
                                        onChange={(e) =>
                                            setData(
                                                'customer_name',
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                    />
                                    {errors.customer_name && (
                                        <p className="mt-1 text-sm text-red-600">
                                            {errors.customer_name}
                                        </p>
                                    )}
                                </div>

                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label className="mb-2 block text-sm font-medium text-black">
                                            Email *
                                        </label>
                                        <input
                                            type="email"
                                            value={data.customer_email}
                                            onChange={(e) =>
                                                setData(
                                                    'customer_email',
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                        />
                                        {errors.customer_email && (
                                            <p className="mt-1 text-sm text-red-600">
                                                {errors.customer_email}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="mb-2 block text-sm font-medium text-black">
                                            Phone Number *
                                        </label>
                                        <input
                                            type="tel"
                                            value={data.customer_phone}
                                            onChange={(e) =>
                                                setData(
                                                    'customer_phone',
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                            placeholder="+234 800 000 0000"
                                        />
                                        {errors.customer_phone && (
                                            <p className="mt-1 text-sm text-red-600">
                                                {errors.customer_phone}
                                            </p>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <label className="mb-2 block text-sm font-medium text-black">
                                        Shipping Address *
                                    </label>
                                    <textarea
                                        value={data.shipping_address}
                                        onChange={(e) =>
                                            setData(
                                                'shipping_address',
                                                e.target.value,
                                            )
                                        }
                                        rows={3}
                                        className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                        placeholder="Street address, apartment, suite, etc."
                                    />
                                    {errors.shipping_address && (
                                        <p className="mt-1 text-sm text-red-600">
                                            {errors.shipping_address}
                                        </p>
                                    )}
                                </div>

                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label className="mb-2 block text-sm font-medium text-black">
                                            City *
                                        </label>
                                        <input
                                            type="text"
                                            value={data.city}
                                            onChange={(e) =>
                                                setData('city', e.target.value)
                                            }
                                            className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                        />
                                        {errors.city && (
                                            <p className="mt-1 text-sm text-red-600">
                                                {errors.city}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="mb-2 block text-sm font-medium text-black">
                                            State *
                                        </label>
                                        <select
                                            value={data.state}
                                            onChange={(e) =>
                                                setData('state', e.target.value)
                                            }
                                            className="w-full rounded-lg border px-4 py-2 text-black focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="">
                                                Select State
                                            </option>
                                            <option value="Lagos">Lagos</option>
                                            <option value="Abuja">Abuja</option>
                                            <option value="Rivers">
                                                Rivers
                                            </option>
                                            <option value="Oyo">Oyo</option>
                                            <option value="Kano">Kano</option>
                                        </select>
                                        {errors.state && (
                                            <p className="mt-1 text-sm text-red-600">
                                                {errors.state}
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="mt-6 w-full rounded-lg bg-blue-600 py-3 font-semibold text-white hover:bg-blue-700 disabled:bg-gray-400"
                            >
                                {processing ? 'Processing...' : 'Place Order'}
                            </button>
                        </form>
                    </div>

                    {/* Order Summary */}
                    <div className="lg:col-span-1">
                        <div className="sticky top-4 rounded-lg bg-white p-6 shadow-sm">
                            <h2 className="mb-4 text-xl font-bold text-black">
                                Order Summary
                            </h2>

                            {/* Items by Merchant */}
                            <div className="mb-6 space-y-4">
                                {itemsByMerchant.map((group) => (
                                    <div
                                        key={group.merchant.id}
                                        className="border-b pb-4"
                                    >
                                        <div className="mb-2 flex items-center">
                                            <img
                                                src={group.merchant.logo}
                                                alt={group.merchant.name}
                                                className="mr-2 h-8 w-8 rounded-full"
                                            />
                                            <span className="text-sm font-semibold text-black">
                                                {group.merchant.name}
                                            </span>
                                        </div>

                                        <div className="ml-10 space-y-2">
                                            {group.items.map((item) => (
                                                <div
                                                    key={item.id}
                                                    className="flex justify-between text-sm"
                                                >
                                                    <span className="text-gray-600">
                                                        {item.product.name} ×{' '}
                                                        {item.quantity}
                                                    </span>
                                                    <span className="text-black">
                                                        ₦
                                                        {item.subtotal.toLocaleString()}
                                                    </span>
                                                </div>
                                            ))}
                                            <div className="flex justify-between pt-2 text-sm font-medium">
                                                <span className="text-black">
                                                    Shipping
                                                </span>
                                                <span className="text-black">
                                                    ₦2,000
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Totals */}
                            <div className="mb-4 space-y-2">
                                <div className="flex justify-between">
                                    <span className="text-gray-600">
                                        Subtotal
                                    </span>
                                    <span className="text-black">
                                        ₦{subtotal.toLocaleString()}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">
                                        Shipping
                                    </span>
                                    <span className="text-black">
                                        ₦{shippingFee.toLocaleString()}
                                    </span>
                                </div>
                            </div>

                            <div className="border-t pt-4">
                                <div className="flex justify-between text-xl font-bold">
                                    <span className="text-black">Total</span>
                                    <span className="text-black">
                                        ₦{total.toLocaleString()}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
