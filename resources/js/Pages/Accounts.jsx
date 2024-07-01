import React from "react";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.jsx";

function Accounts() {
    return (
        <AuthenticatedLayout>
            <div className="container mx-auto px-4">
                <h1 className="text-2xl font-bold justify-center flex">Accounts</h1>
                <p className="justify-center flex">Welcome to the Accounts page.</p>
            </div>
        </AuthenticatedLayout>
    );
}

export default Accounts;
