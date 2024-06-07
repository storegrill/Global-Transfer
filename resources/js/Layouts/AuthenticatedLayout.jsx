import React from "react";
import Sidebar from "@/Components/Sidebar";
import Dropdown from "@/Components/Dropdown";
import { usePage } from "@inertiajs/react";

export default function Authenticated({ children }) {
    const { auth } = usePage().props;
    return (
        <div className="flex min-h-screen bg-gradient-to-b from-sky-600 to-cyan-100">
            <Sidebar />
            <div className="absolute top-0 right-0 p-4">
                {" "}
                <Dropdown username={auth.user.name} />
            </div>
            <div className="flex-grow p-4">{children}</div>
        </div>
    );
}
