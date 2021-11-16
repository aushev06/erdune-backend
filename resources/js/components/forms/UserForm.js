/* This example requires Tailwind CSS v2.0+ */
import React, {Fragment, useRef, useState} from 'react'
import {Dialog, Transition} from '@headlessui/react'
import {ExclamationIcon} from '@heroicons/react/outline'
import {Form} from "./form";
import ReactDOM from "react-dom";

function UserForm() {
    const [open, setOpen] = useState(false)
    const [data, setData] = useState(null);
    const cancelButtonRef = useRef(null)

    React.useEffect(() => {
        console.log(window.$)
        window.$('.user-form').on('click', (e) => {
            setData(JSON.parse(e.target.dataset.json))
            setOpen(true)
        });

    }, [])

    const handleChange = (e) => {
        setData({
            ...data,
            [e.target.name]: e.target.value
        })
    }

    const handleClick = async () => {
        await axios.put(`/admin/users/${data.id}`, data);
        setOpen(false);
    }

    return (
        <Form setOpen={setOpen} open={open} data={data} handleChange={handleChange} handleClick={handleChange}>
            <form action="#" method="POST">
                <div className="shadow overflow-hidden sm:rounded-md">
                    <div className="px-4 py-5 bg-white sm:p-6">
                        <div className="grid grid-cols-6 gap-6">
                            <div className="col-span-6 sm:col-span-3">
                                <label htmlFor="first-name"
                                       className="block text-sm font-medium text-gray-700">
                                    Имя
                                </label>
                                <input
                                    defaultValue={data?.name}
                                    onChange={handleChange}
                                    type="text"
                                    name="name"
                                    id="name"
                                    autoComplete="given-name"
                                    className="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <div className="col-span-6 sm:col-span-3">
                                <label htmlFor="email-address"
                                       className="block text-sm font-medium text-gray-700">
                                    Почта
                                </label>
                                <input
                                    defaultValue={data?.email}
                                    onChange={handleChange}
                                    type="text"
                                    name="email"
                                    id="email"
                                    autoComplete="email"
                                    className="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>

                            <div className="col-span-6 sm:col-span-3">
                                <label htmlFor="country" className="block text-sm font-medium text-gray-700">
                                    Статус
                                </label>
                                <select
                                    onChange={handleChange}
                                    defaultValue={data?.status}
                                    id="status"
                                    name="status"
                                    autoComplete="status"
                                    className="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value={'active'}>active</option>
                                    <option value={'disabled'}>disabled</option>
                                    <option value={'blocked'}>blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </Form>
    )
}

if ($('.user-form').length && document.getElementById('react-form')) {
    ReactDOM.render(<UserForm/>, document.getElementById('react-form'));
}
