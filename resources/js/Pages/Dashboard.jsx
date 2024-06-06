import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react";
import Navbar from "../Components/Navbar";
import backgroundImage from "../../../public/6927689.jpg";

import Sidebar from "@/Components/Sidebar";
import Dropdown from "@/Components/Dropdown";

export default function Dashboard() {
    const {auth} = usePage().props;
    const username = auth.user.name;

    return (
        <div
            className="bg-white"
            
        >
            <Sidebar/>
            <div className="absolute top-0 right-0 p-4"> {/* Adjusted position to top right */}
                <Dropdown username={auth.user.name}/>
            </div>
        </div>
    );
}
