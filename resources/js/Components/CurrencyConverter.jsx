import React, { useEffect, useState } from "react";
import { currencyList } from "./CurrencyList";

export default function CurrencyConverter() {
    const [amountFrom, setAmountFrom] = useState("");
    const [amountTo, setAmountTo] = useState("");
    const [convertFrom, setConvertFrom] = useState("");
    const [convertTo, setConvertTo] = useState("");
    const [exchangeRate, setExchangeRate] = useState(null);
    const [error, setError] = useState(null);
    const apiKey = "f4f2523c1b633fc750232b5c";

    useEffect(() => {
        if (convertFrom && convertTo) {
            fetchExchangeRate();
        }
    }, [convertFrom, convertTo, amountFrom]);

    const fetchExchangeRate = async () => {
        const apiUrl = `https://v6.exchangerate-api.com/v6/${apiKey}/pair/${convertFrom}/${convertTo}`;
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();
            if (data.result === "success") {
                setExchangeRate(data.conversion_rate);
                setAmountTo((amountFrom * data.conversion_rate).toFixed(2));
            } else {
                setError("Failed to fetch exchange rate.");
            }
        } catch (error) {
            setError("Error fetching exchange rate.");
        }
    };

    const handleSwap = () => {
        setConvertFrom(convertTo);
        setConvertTo(convertFrom);
    };

    return (
        <div className="container mx-auto p-4 relative top-7">
            <div className="relative bottom-12">
                <p className="text-2xl text-start text-white font-bold leading-4 mb-4">
                    Send Money,
                </p>
                <p className="text-2xl text-start text-white font-bold leading-4 mb-4">
                    Anywhere,
                </p>
                <p className="text-2xl text-start text-white font-bold leading-4 mb-4">
                    Anytime
                </p>
            </div>

            <div className="p-6">
                <div className="flex flex-wrap justify-between mb-4">
                    <div className="w-full md:w-3/5 mb-2 md:mb-0">
                        <input
                            type="number"
                            value={amountFrom}
                            onChange={(e) => setAmountFrom(e.target.value)}
                            placeholder="Amount"
                            className="input-field w-[400px] h-14 p-2 border border-gray-300 rounded-2xl"
                        />
                    </div>
                    <div className="w-full md:w-2/5">
                        <select
                            value={convertFrom}
                            onChange={(e) => setConvertFrom(e.target.value)}
                            className="select-field w-full h-14 p-2 border border-gray-300 rounded-2xl"
                        >
                            <option value="">Convert</option>
                            {currencyList.map((currency) => (
                                <option
                                    key={currency.code}
                                    value={currency.code}
                                >
                                    {currency.name}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>
                <div className="flex flex-wrap justify-between">
                    <div className="w-full md:w-3/5 mb-2 md:mb-0">
                        <input
                            type="number"
                            value={amountTo}
                            readOnly
                            placeholder="Converted amount"
                            className="input-field w-[400px] h-14 p-2 border border-gray-300 rounded-2xl"
                        />
                    </div>
                    <div className="w-full md:w-2/5">
                        <select
                            value={convertTo}
                            onChange={(e) => setConvertTo(e.target.value)}
                            className="select-field w-full h-14 p-2 border border-gray-300 rounded-2xl"
                        >
                            <option value="">Convert</option>
                            {currencyList.map((currency) => (
                                <option
                                    key={currency.code}
                                    value={currency.code}
                                >
                                    {currency.name}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>
                {exchangeRate && (
                    <div className="exchange-rate text-white text-center mt-3">
                        1 {convertFrom} = {exchangeRate} {convertTo}
                    </div>
                )}
                {error && (
                    <div className="error text-red-500 text-center mt-3">
                        {error}
                    </div>
                )}
                <button
                    className="swap-currency bg-white text-black hover:bg-blue-500 hover:text-white p-2 mt-3 rounded-2xl mb-1"
                    onClick={handleSwap}
                >
                    Swap
                </button>
            </div>
        </div>
    );
}
