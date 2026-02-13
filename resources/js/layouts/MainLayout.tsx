import { Link, usePage } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { PageProps } from '@/types';

export default function MainLayout({ children }: PropsWithChildren) {
    const { cart, auth, flash } = usePage<PageProps>().props;

    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white shadow-sm">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between">
                        <Link
                            href="/"
                            className="text-2xl font-bold text-blue-600"
                        >
                            Bumpa Market
                        </Link>

                        <nav className="flex items-center space-x-6">
                            <Link
                                href="/"
                                className="text-gray-700 hover:text-blue-600"
                            >
                                Products
                            </Link>

                            <Link
                                href="/cart"
                                className="relative text-gray-700 hover:text-blue-600"
                            >
                                <svg
                                    className="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                {cart && cart.items_count > 0 && (
                                    <span className="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-xs text-white">
                                        {cart.items_count}
                                    </span>
                                )}
                            </Link>

                            {auth && auth.user ? (
                                <>
                                    <Link
                                        href="/dashboard"
                                        className="text-gray-700 hover:text-blue-600"
                                    >
                                        Dashboard
                                    </Link>
                                    <Link
                                        href="/logout"
                                        method="post"
                                        className="text-gray-700 hover:text-blue-600"
                                    >
                                        Logout
                                    </Link>
                                </>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="text-gray-700 hover:text-blue-600"
                                    >
                                        Login
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="text-gray-700 hover:text-blue-600"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </nav>
                    </div>
                </div>
            </header>

            {/* Flash Messages */}
            {flash?.success && (
                <div className="bg-green-500 px-4 py-3 text-center text-white">
                    {flash.success}
                </div>
            )}
            {flash?.error && (
                <div className="bg-red-500 px-4 py-3 text-center text-white">
                    {flash.error}
                </div>
            )}

            <main>{children}</main>

            <footer className="mt-12 border-t bg-white">
                <div className="container mx-auto px-4 py-8">
                    <p className="text-center text-gray-600">
                        © 2024 Bumpa Market. Multi-vendor e-commerce platform.
                    </p>

                    <Link
                        className="mx-auto mt-2 block text-center text-gray-600 underline underline-offset-4 hover:text-blue-600"
                        href={`https://emmanuelolawuni.com.ng`}
                    >
                        Made by Emmanuel Olawuni
                    </Link>
                </div>
            </footer>
        </div>
    );
}
