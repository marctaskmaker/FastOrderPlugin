import template from "./fast-order-list.html.twig";

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register("fast-order-list", {
  template,

  inject: ["repositoryFactory"],

  data() {
    return {
      fastOrders: [],
      fastOrderColumns: [
        { property: "product", label: "Product" },
        { property: "quantity", label: "Quantity" },
        { property: "session", label: "Session" },
        { property: "created_at", label: "Created" },
        { property: "updated_at", label: "Updated" },
        { property: "comment", label: "Comment" },
      ],
      isLoading: false,
      showDeleteModal: false,
    };
  },

  computed: {
    fastOrderRepository() {
      return this.repositoryFactory.create("fast_order");
    },
  },

  created() {
    this.getFastOrders();
  },

  methods: {
    getFastOrders: function () {
      this.fastOrderRepository
        .search(new Criteria(), Shopware.Context.api)
        .then((result) => {
          this.fastOrders = result;
        });
    },

    onDelete(id) {
      this.showDeleteModal = id;
    },

    onCloseDeleteModal() {
      this.showDeleteModal = false;
    },

    onConfirmDelete(id) {
      this.showDeleteModal = false;

      return this.fastOrderRepository.delete(id).then(() => {
        this.getFastOrders();
      });
    },
  },

  metaInfo() {
    return {
      title: this.$createTitle(),
    };
  },
});
