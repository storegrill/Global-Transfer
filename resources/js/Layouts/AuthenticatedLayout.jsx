import React from "react";
import Sidebar from "@/Components/Sidebar";
import DashboardNavbar from "@/Components/DashboardNavbar";
import Dropdown from "@/Components/Dropdown";
import { usePage } from "@inertiajs/react";

import BackgroundImage from "../../../public/6927689.jpg";

export default function Authenticated({ children }) {
    const { auth } = usePage().props;
    return (
        <div className="flex flex-col min-h-screen bg-gradient-to-b from-blue-700 to-cyan-200">
            <DashboardNavbar />
            <div className="flex flex-grow">
                <Sidebar />
                <div className="flex-grow p-4">{children}</div>
            </div>
            <div className="absolute top-0 right-0 p-4">
                {/* <Dropdown username={auth.user.name} /> */}
            </div>
        </div>
    );
}
