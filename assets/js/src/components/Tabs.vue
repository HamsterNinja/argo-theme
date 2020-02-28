<template>
  <div>
    <div class="tabs">
      <div v-for="(tab, index) in tabs" :class="{ 'is-active': tab.isActive }" :key='index'>
        <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
      </div>
    </div>

    <div class="tabs-details">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { tabs: [] };
  },

  created() {
    this.tabs = this.$children;
  },
  methods: {
    selectTab(selectedTab) {
      this.tabs.forEach(tab => {
        tab.isActive = tab.name == selectedTab.name;
      });
    }
  }
};
</script>