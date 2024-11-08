import React, { useEffect, useState } from "react";
import "./header.css";
import { Link, NavLink, useNavigate } from "react-router-dom";
import { Category } from "../../interface/Category";
import { IProduct } from "../../interface/IProduct";

type Props = {};

const Header = (props: Props) => {
    const [categories, setCategories] = useState<Category[]>([]);
    const navigate = useNavigate();
    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const response = await fetch(
                    " http://localhost:3000/categories"
                );
                const data = await response.json();
                setCategories(data);
                // console.log(data);
            } catch (error) {
                console.error("Failed to fetch categories", error);
            }
        };

        fetchCategories();
    }, []);
    const handleCategoryClick = (slug: string) => {
        navigate(`/products/${slug}`);
    };

    // products
    const [products, setProducts] = useState<IProduct[]>([]);
    const [searchInput, setSearchInput] = useState("");
    useEffect(() => {
        const fetchProducts = async () => {
            try {
                const response = await fetch("http://localhost:3000/products");
                const data = await response.json();
                setProducts(data);
                // console.log(data);
            } catch (error) {
                console.error("Failed to fetch products", error);
            }
        };

        fetchProducts();
    }, []);
    const filteredProducts = products.filter((item) =>
        item.name.toLowerCase().includes(searchInput.toLowerCase())
    );
    return (
        <>
            <div className="header">
                <div className="container">
                    <div className="row-flex">
                        <div className="header-logo mt-1">
                            <Link to="/">
                                <img
                                    src="../../../public/image/logo1.png"
                                    alt="Logo"
                                />
                            </Link>
                        </div>
                        <div
                            className="header-nav"
                            style={{
                                display: "flex",
                                marginLeft: "10px",
                                color: "black",
                            }}
                        >
                            {categories.map((parent) => (
                                <ul key={parent.id}>
                                    <li
                                        className="dropdown"
                                        style={{
                                            marginLeft: "20px",
                                            color: "black",
                                        }}
                                    >
                                        <Link
                                            to="#"
                                            onClick={() =>
                                                handleCategoryClick(parent.slug)
                                            }
                                            style={{ color: "black" }}
                                        >
                                            {parent.name}
                                        </Link>
                                        {parent.children &&
                                            parent.children.length > 0 && (
                                                <ul className="dropdown-menu">
                                                    {parent.children.map(
                                                        (child) => (
                                                            <li key={child.id}>
                                                                <Link
                                                                    to={`/products/${child.slug}`}
                                                                    style={{
                                                                        color: "black",
                                                                    }}
                                                                >
                                                                    {child.name}
                                                                </Link>
                                                            </li>
                                                        )
                                                    )}
                                                </ul>
                                            )}
                                    </li>
                                </ul>
                            ))}
                            <li>
                                <Link
                                    to={`/contact`}
                                    style={{
                                        marginLeft: "20px",
                                        color: "black",
                                    }}
                                >
                                    Liên Hệ
                                </Link>
                            </li>
                            <li>
                                <Link
                                    to={`/introduce`}
                                    style={{
                                        marginLeft: "20px",
                                        color: "black",
                                    }}
                                >
                                    Giới Thiệu
                                </Link>
                            </li>
                        </div>
                        <div className="header-search">
                            <form action="" style={{ position: "relative" }}>
                                <input
                                    type="text"
                                    placeholder="Nhập tên sản phẩm cần tìm"
                                    value={searchInput}
                                    onChange={(e) =>
                                        setSearchInput(e.target.value)
                                    }
                                    style={{ paddingLeft: "50px" }}
                                />
                                <i
                                    className="ri-search-line"
                                    style={{
                                        fontSize: "1.5rem",
                                        marginLeft: "10px",
                                        color: "gray",
                                    }}
                                ></i>
                                {searchInput && (
                                    <div
                                        className="search-container"
                                        style={{
                                            backgroundColor: "white",
                                            position: "absolute",
                                            width: "400px",
                                        }}
                                    >
                                        {filteredProducts.length > 0 ? (
                                            filteredProducts.map(
                                                (item, index) => (
                                                    <div
                                                        className="search-item"
                                                        key={index}
                                                        style={{
                                                            display: "flex",
                                                            cursor: "pointer",
                                                        }}
                                                        onClick={() =>
                                                            navigate(
                                                                `/product-detail/${item.id}`
                                                            )
                                                        }
                                                    >
                                                        <img
                                                            src={item.image}
                                                            alt={item.name}
                                                            width={"50px"}
                                                            style={{
                                                                marginTop:
                                                                    "12px",
                                                                marginLeft:
                                                                    "10px",
                                                            }}
                                                        />
                                                        <div className="search-info">
                                                            <h5
                                                                style={{
                                                                    marginTop:
                                                                        "20px",
                                                                    marginLeft:
                                                                        "15px",
                                                                }}
                                                            >
                                                                {item.name}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                )
                                            )
                                        ) : (
                                            <p>Không tìm thấy sản phẩm nào.</p>
                                        )}
                                    </div>
                                )}
                            </form>
                        </div>
                        <div className="header-card">
                            <div
                                style={{
                                    position: "relative",
                                    display: "inline-block",
                                }}
                            >
                                <i
                                    className="ri-shopping-cart-line"
                                    style={{
                                        fontSize: "1.5rem",
                                    }}
                                ></i>
                                <span
                                    style={{
                                        position: "absolute",
                                        right: -10,
                                        top: -10,
                                        backgroundColor: "black",
                                        color: "white",
                                        borderRadius: "50%",
                                        padding: "2px 4px",
                                        fontSize: "12px",
                                    }}
                                >
                                    9
                                </span>
                            </div>
                        </div>
                        <div className="header-user mr-20">
                            <a href="/login">
                                <i
                                    className="ri-user-3-fill"
                                    style={{
                                        fontSize: "1.5rem",
                                    }}
                                ></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default Header;