import React from "react";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.jsx";

export default function Card() {
    return (
        <AuthenticatedLayout>
            <div className="container mx-auto px-4">
                <h1 className="text-2xl font-bold justify-center flex">Card</h1>
                <p className="justify-center flex">Welcome to the Card page.</p>
            </div>
        </AuthenticatedLayout>
    );
}