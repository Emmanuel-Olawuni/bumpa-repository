import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';

interface OrderGroup {
    id: number;
    merchant: {
        name: string;
        logo: string;
    };
    subtotal: number;
    shipping_fee: number;
    status: string;
    items: Array<{
        product: {
            name: string;
            image: string;
        };
        quantity: number;
        price: number;
    }>;
}

interface Order {
    id: number;
    order_number: string;
    total_amount: number;
    shipping_fee: number;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    shipping_address: string;
    city: string;
    state: string;
    order_groups: OrderGroup[];
}

interface Props {
    order: Order;
}

export default function CheckoutSuccess({ order }: Props) {
    return (
        <MainLayout>
            <Head title="Order Confirmed" />

            <div className="container mx-auto px-4 py-8">
                <div className="mx-auto max-w-3xl">
                    {/* Success Message */}
                    <div className="mb-8 rounded-lg border border-green-200 bg-green-50 p-6 text-center">
                        <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-500">
                            <svg
                                className="h-8 w-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                        <h1 className="mb-2 text-2xl font-bold text-green-900">
                            Order Confirmed!
                        </h1>
                        <p className="text-green-700">
                            Thank you for your order. We've sent a confirmation
                            to {order.customer_email}
                        </p>
                    </div>

                    {/* Order Details */}
                    <div className="mb-6 rounded-lg bg-white p-6 shadow-sm">
                        <h2 className="mb-4 text-xl font-bold text-black">
                            Order Details
                        </h2>

                        <div className="mb-6 grid gap-4 md:grid-cols-2">
                            <div>
                                <p className="text-sm text-gray-600">
                                    Order Number
                                </p>
                                <p className="font-semibold text-black">
                                    {order.order_number}
                                </p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">
                                    Total Amount
                                </p>
                                <p className="font-semibold text-black">
                                    ₦
                                    {Number(
                                        order.total_amount,
                                    ).toLocaleString()}
                                </p>
                            </div>
                        </div>

                        <div className="border-t pt-4">
                            <p className="mb-2 text-sm text-gray-600">
                                Shipping Address
                            </p>
                            <p className="font-medium text-black">
                                {order.customer_name}
                            </p>
                            <p className="text-gray-700">
                                {order.shipping_address}
                            </p>
                            <p className="text-gray-700">
                                {order.city}, {order.state}
                            </p>
                            <p className="text-gray-700">
                                {order.customer_phone}
                            </p>
                        </div>
                    </div>

                    {/* Items by Merchant */}
                    {order.order_groups.map((group) => (
                        <div
                            key={group.id}
                            className="mb-6 rounded-lg bg-white p-6 shadow-sm"
                        >
                            <div className="mb-4 flex items-center border-b pb-4">
                                <img
                                    src={group.merchant.logo}
                                    alt={group.merchant.name}
                                    className="mr-3 h-12 w-12 rounded-full"
                                />
                                <div>
                                    <h3 className="font-bold text-black">
                                        {group.merchant.name}
                                    </h3>
                                    <p className="text-sm text-gray-600">
                                        Status:{' '}
                                        <span className="text-blue-600 capitalize">
                                            {group.status}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            {group.items.map((item, index) => (
                                <div
                                    key={index}
                                    className="flex items-center space-x-4 border-b py-3 last:border-0"
                                >
                                    <img
                                        src={item.product.image}
                                        alt={item.product.name}
                                        className="h-16 w-16 rounded object-cover"
                                    />
                                    <div className="flex-1">
                                        <p className="font-semibold text-black">
                                            {item.product.name}
                                        </p>
                                        <p className="text-sm text-gray-600">
                                            Quantity: {item.quantity}
                                        </p>
                                    </div>
                                    <p className="font-semibold text-black">
                                        ₦
                                        {(
                                            item.price * item.quantity
                                        ).toLocaleString()}
                                    </p>
                                </div>
                            ))}

                            <div className="mt-4 flex justify-between border-t pt-4">
                                <span className="text-gray-600">
                                    Subtotal + Shipping
                                </span>
                                <span className="font-bold text-black">
                                    ₦
                                    {(
                                        Number(group.subtotal) +
                                        Number(group.shipping_fee)
                                    ).toLocaleString()}
                                </span>
                            </div>
                        </div>
                    ))}

                    {/* Actions */}
                    <div className="flex justify-center space-x-4">
                        <Link
                            href="/"
                            className="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
