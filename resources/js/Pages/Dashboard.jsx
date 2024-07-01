import { usePage } from "@inertiajs/react";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.jsx";

export default function Dashboard() {
    const { auth } = usePage().props;

    return (
        <AuthenticatedLayout>
            <div className="container mx-auto px-4 min-h-screen flex flex-col">
                <div className="flex-grow flex flex-col justify-between">
                    {/* First Row */}
                    <div className="flex flex-wrap -mx-2 mb-4 flex-grow">
                        <div className="w-full md:w-1/3 px-6 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-2 font-bold text-xl">
                                My cards
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                Digital Card 1
                            </div>
                        </div>
                        <div className="w-full md:w-1/3 px-6 mb-7">
                            <a href="">
                                <p className="text-end text-white relative right-1 pb-2 pt-4 top-1">
                                    See all
                                </p>
                            </a>

                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                            Digital Card 2
                            </div>
                        </div>
                        <div className="w-full md:w-1/3 px-6 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-2 text-xl font-bold">
                                Recent Transactions
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                Deposit
                            </div>
                        </div>
                    </div>

                    {/* Second Row */}
                    <div className="flex flex-wrap -mx-2 mb-6 flex-grow">
                        <div className="w-full md:w-3/5 px-5 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-4 text-xl font-bold">
                                Weekly Activity
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                Card 4
                            </div>
                        </div>
                        <div className="w-full md:w-2/5 px-6 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-4 text-xl font-bold">
                                Expense Statistics
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                Pie Chart
                            </div>
                        </div>
                    </div>

                    {/* Third Row */}
                    <div className="flex flex-wrap -mx-2 mb-4 flex-grow">
                        <div className="w-full md:w-1/2 px-6 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-4 text-xl font-bold">
                                Quick Transfer
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                Quick transfer
                            </div>
                        </div>
                        <div className="w-full md:w-1/2 px-6 mb-7">
                            <p className="text-white relative left-1 pb-3 pt-4 text-xl font-bold">
                                Balance History
                            </p>
                            <div className="bg-white shadow-md rounded-3xl p-4 h-full">
                                History
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
