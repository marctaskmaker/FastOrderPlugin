import template from "./fast-order-detail.html.twig";

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register("fast-order-detail", {
  template,

  inject: ["repositoryFactory"],

  mixins: [Mixin.getByName("notification")],

  props: {
    fastOrderId: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      fastOrder: [],
      isLoading: false,
      isSaveSuccessful: false,
    };
  },

  computed: {
    fastOrderRepository() {
      return this.repositoryFactory.create("fast_order");
    },
  },

  created() {
    this.getFastOrder();
  },

  methods: {
    getFastOrder: function () {
      this.fastOrderRepository
        .get(this.fastOrderId, Shopware.Context.api, new Criteria())
        .then((result) => {
          this.fastOrder = result;
        });
    },

    saveFinish() {
      this.isSaveSuccessful = false;
      this.isLoading = false;
    },

    async onSave() {
      this.isLoading = true;
      this.isSaveSuccessful = false;

      return this.fastOrderRepository
        .save(this.fastOrder)
        .then(() => {
          this.isLoading = false;
          this.isSaveSuccessful = true;

          this.createNotificationSuccess({
            message: this.$tc("fast-order.detail.messageSaveSuccess"),
          });
        })
        .catch((exception) => {
          this.createNotificationError({
            message: this.$tc("fast-order.detail.messageSaveError"),
          });
          this.isLoading = false;
          throw exception;
        });
    },

    onCancel() {
      this.$router.push({ name: "fast.order.list" });
    },
  },

  metaInfo() {
    return {
      title: this.$createTitle(),
    };
  },
});
