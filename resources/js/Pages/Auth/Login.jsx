import { useForm } from "@inertiajs/react";

import CurrencyConverter from "@/Components/CurrencyConverter";
import { UserCircle } from "lucide-react";

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: "",
        password: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("login"));
    };

    return (
        <div className="flex min-h-screen bg-gradient-to-b from-blue-700 to-cyan-200">
            <div className="w-1/2 flex flex-col justify-center items-center text-white p-8">
                <form className="w-full max-w-sm p-14 rounded-lg">
                    <div className="mb-0 pb-2">
                        <h1 className="text-7xl font-bold mt-4 text-white pb-6">
                            Global Transfer
                        </h1>
                        <input
                            className="shadow appearance-none border rounded-2xl w-[400px] h-14 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="username"
                            type="text"
                            placeholder="Email"
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                        />
                         <UserCircle className="relative h-5 w-5 text-black left-[350px] bottom-[37px]" />
                        {errors.email && (
                            <span className="text-red-500 text-sm">
                                {errors.email}
                            </span>
                        )}
                    </div>
                    <div className="mb-0">
                        <input
                            className="shadow appearance-none border rounded-2xl w-[400px] h-14 py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            id="password"
                            type="password"
                            placeholder="******************"
                            value={data.password}
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                        />
                        <UserCircle className="relative h-5 w-5 text-black left-[350px] bottom-[50px]" />
                        {errors.password && (
                            <span className="text-red-500 text-sm">
                                {errors.password}
                            </span>
                        )}
                    </div>
                    <div className="">
                        <button
                            className="bg-white w-[400px] h-14 hover:bg-blue-500 hover:text-white text-black font-bold py-2 px-4 rounded-2xl focus:outline-none focus:shadow-outline"
                            type="button"
                            onClick={handleSubmit}
                            disabled={processing}
                        >
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
            <div className="w-1/2 flex justify-center items-center p-8">
                <div className="text-center">
                    <CurrencyConverter/>
                </div>
            </div>
        </div>
    );
}
