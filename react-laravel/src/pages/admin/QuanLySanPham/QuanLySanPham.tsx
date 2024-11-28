import React, { useState, useEffect } from "react";
import { Space, Table, Button, Modal, message, Tooltip } from "antd";
import { EditOutlined, DeleteOutlined, PlusOutlined } from "@ant-design/icons";
import "../../../style/quanLy.css";
import api from "../../../config/axios";
import FormSanPham from "./FormSanPham";
import { IProduct } from "../../../interface/IProduct";

const QuanLySanPham = () => {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [modalLoading, setModalLoading] = useState(false); // Loading riêng cho modal
    const [loading, setLoading] = useState(true);
    const [products, setProducts] = useState<IProduct[]>([]);
    const [currentProduct, setCurrentProduct] = useState<IProduct | null>(null);
    const [pagination, setPagination] = useState({
        current: 1,
        pageSize: 10,
        total: 0,
    });

    const fetchProducts = async (page = 1) => {
        setLoading(true);
        try {
            const response = await api.get(
                `admin/products/list-product?page=${page}`
            );
            const { data, total, per_page, current_page } = response.data;
            setProducts(data || []);
            setPagination({
                current: current_page,
                pageSize: per_page,
                total: total,
            });
        } catch (error) {
            console.error("Lỗi khi lấy danh sách sản phẩm:", error);
            message.error("Không thể tải danh sách sản phẩm.");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchProducts(pagination.current);
    }, [pagination.current]);

    const handleAddProduct = () => {
        setCurrentProduct(null);
        setIsModalOpen(true);
    };

    const handleEdit = (record: IProduct) => {
        setCurrentProduct(record);
        setIsModalOpen(true);
    };

    const handleDelete = (product_id: number) => {
        Modal.confirm({
            title: "Bạn có chắc chắn muốn xóa sản phẩm này?",
            onOk: async () => {
                try {
                    await api.delete(
                        `admin/products/destroy-product/${product_id}`
                    );
                    setProducts((prev) =>
                        prev.filter(
                            (product) => product.product_id !== product_id
                        )
                    );
                    setPagination((prev) => ({
                        ...prev,
                        total: prev.total - 1,
                    }));
                    await fetchProducts(pagination.current);
                    message.success("Xóa sản phẩm thành công");
                } catch (error) {
                    console.error("Xóa sản phẩm thất bại:", error);
                    message.error("Không thể xóa sản phẩm.");
                }
            },
        });
    };

    const handleOk = async (values: IProduct) => {
        setModalLoading(true);
        try {
            if (currentProduct) {
                const response = await api.put(
                    `admin/products/update-product/${currentProduct.product_id}`,
                    values
                );
                const updatedProduct = response.data;

                setProducts((prevProducts) =>
                    prevProducts.map((product) =>
                        product.product_id === updatedProduct.product_id
                            ? { ...product, ...updatedProduct }
                            : product
                    )
                );
                await fetchProducts(pagination.current);
                message.success("Cập nhật sản phẩm thành công");
            } else {
                const response = await api.post(
                    "admin/products/add-product",
                    values
                );
                await fetchProducts(pagination.current);
                message.success("Thêm sản phẩm thành công");
            }
            setIsModalOpen(false);
        } catch (error) {
            // console.error("Lỗi khi thêm/sửa sản phẩm:", error);
            // message.error("Không thể thêm hoặc sửa sản phẩm.");
        } finally {
            setModalLoading(false);
        }
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    return (
        <div className="quan-ly-container">
            <div className="header">
                <p className="title-css">Quản lý sản phẩm</p>
            </div>
            <div className="table">
                <Button
                    type="primary"
                    icon={<PlusOutlined />}
                    onClick={handleAddProduct}
                    style={{ marginBottom: "10px", float: "right" }}
                >
                    Thêm mới
                </Button>

                <Table
                    rowKey={(record) =>
                        record.product_id || `temp-${Date.now()}`
                    }
                    columns={[
                        {
                            title: "STT",
                            key: "index",
                            render: (text, record, index) => (
                                <span
                                    style={{
                                        display: "flex",
                                        justifyContent: "center",
                                    }}
                                >
                                    {index + 1}
                                </span>
                            ),
                            align: "center" as "center",
                            width: "5%",
                        },
                        {
                            title: "Tên sản phẩm",
                            dataIndex: "name",
                            key: "name",
                            render: (text) => (
                                <a style={{ color: "green" }}>{text}</a>
                            ),
                            align: "center" as "center",
                        },
                        {
                            title: "Mô tả",
                            dataIndex: "description",
                            key: "description",
                            render: (text) => <span>{text}</span>,
                            align: "center" as "center",
                        },
                        {
                            title: "Mã sản phẩm",
                            dataIndex: "sku",
                            key: "sku",
                            render: (text) => <span>{text}</span>,
                            align: "center" as "center",
                        },
                        {
                            title: "Trạng thái",
                            dataIndex: "is_active",
                            key: "is_active",
                            render: (text) => (
                                <span>
                                    {text ? "Hoạt động" : "Không hoạt động"}
                                </span>
                            ),
                            align: "center" as "center",
                        },
                        {
                            key: "action",
                            render: (text, record) => (
                                <Space size="middle">
                                    <Tooltip placement="top" title="Chỉnh sửa">
                                        <EditOutlined
                                            style={{ color: "orange" }}
                                            onClick={() => handleEdit(record)}
                                        />
                                    </Tooltip>
                                    <Tooltip placement="top" title="Xóa">
                                        <DeleteOutlined
                                            style={{ color: "red" }}
                                            onClick={() =>
                                                handleDelete(record.product_id)
                                            }
                                        />
                                    </Tooltip>
                                </Space>
                            ),
                            align: "center" as "center",
                        },
                    ]}
                    dataSource={products}
                    loading={loading}
                    pagination={{
                        current: pagination.current,
                        pageSize: pagination.pageSize,
                        total: pagination.total,
                        onChange: (page) =>
                            setPagination((prev) => ({
                                ...prev,
                                current: page,
                            })),
                    }}
                />
            </div>

            <FormSanPham
                open={isModalOpen}
                onCancel={handleCancel}
                onOk={handleOk}
                initialValues={currentProduct}
                loading={modalLoading}
            />
        </div>
    );
};

export default QuanLySanPham;
