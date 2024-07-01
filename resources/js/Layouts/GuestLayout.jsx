import Navbar from '@/Components/Navbar';
import { Link } from '@inertiajs/react';

export default function Guest({ children }) {
    return (
        <div className="flex flex-col min-h-screen bg-gradient-to-b from-blue-700 to-cyan-200">
            <Navbar />
            <div className="flex flex-grow">
                <div className="flex-grow p-4">{children}</div>
            </div>
            <div className="absolute top-0 right-0 p-4">
                {/* <Dropdown username={auth.user.name} /> */}
            </div>
        </div>
    );
}
