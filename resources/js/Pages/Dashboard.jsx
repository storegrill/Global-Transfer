import { usePage } from "@inertiajs/react";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.jsx";

export default function Dashboard() {
    const { auth } = usePage().props;

    return (
        <AuthenticatedLayout>
            <div className="container mx-auto px-4">
                <h1 className="text-2xl font-bold justify-center flex">Dashboard</h1>
                <p className="justify-center flex">Welcome to the Global Transfer</p>
            </div>
        </AuthenticatedLayout>
    );
}
