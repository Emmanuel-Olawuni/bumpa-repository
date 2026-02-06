import { Head } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import MerchantSection from '@/components/merchant-section';
import { Merchant, Product } from '@/types';

interface Props {
    productsByMerchant: {
        merchant: Merchant;
        products: Product[];
    }[];
}

export default function ProductsIndex({ productsByMerchant }: Props) {
    return (
        <MainLayout>
            <Head title="Shop All Products" />

            <div className="container mx-auto px-4 py-8">
                <h1 className="mb-2 text-4xl font-bold text-gray-900">
                    Shop from Top Merchants
                </h1>
                <p className="mb-8 text-gray-600">
                    Discover products from verified sellers across Nigeria
                </p>

                {productsByMerchant.map((group) => (
                    <MerchantSection
                        key={group.merchant.id}
                        merchant={group.merchant}
                        products={group.products}
                    />
                ))}
            </div>
        </MainLayout>
    );
}
