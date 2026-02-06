import { Merchant, Product } from '@/types';
import ProductCard from '@/components/product-card';

interface Props {
    merchant: Merchant;
    products: Product[];
}

export default function MerchantSection({ merchant, products }: Props) {
    return (
        <div className="mb-12">
            {/* Merchant Header */}
            <div className="mb-6 flex items-center border-b pb-4">
                <img
                    src={merchant.logo}
                    alt={merchant.name}
                    className="mr-4 h-16 w-16 rounded-full"
                />
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">
                        {merchant.name}
                    </h2>
                    <p className="text-gray-600">{merchant.description}</p>
                </div>
            </div>

            {/* Products Grid */}
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {products.map((product) => (
                    <ProductCard key={product.id} product={product} />
                ))}
            </div>
        </div>
    );
}
