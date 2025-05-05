document.addEventListener("DOMContentLoaded", () => {
  // Sidebar Toggle
  const sidebar = document.getElementById("sidebar")
  const sidebarToggle = document.getElementById("sidebarToggle")
  const sidebarCollapseBtn = document.getElementById("sidebarCollapseBtn")
  const mainContent = document.querySelector('.main-content')
  const adminFooter = document.querySelector('.admin-footer')

  // Lấy kích thước sidebar từ localStorage nếu có
  const savedSidebarWidth = localStorage.getItem("sidebarWidth")
  if (savedSidebarWidth) {
    sidebar.style.width = savedSidebarWidth
    document.documentElement.style.setProperty("--sidebar-width", savedSidebarWidth)
  }

  // Kiểm tra trạng thái collapsed từ localStorage
  const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true"
  if (isCollapsed) {
    sidebar.classList.add("collapsed")
    if (mainContent) {
      mainContent.style.marginLeft = "50px";
      mainContent.style.width = "calc(100% - 50px)";
    }
    if (adminFooter) {
      adminFooter.style.left = "50px";
      adminFooter.style.width = "calc(100% - 50px)";
    }
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
      if (window.innerWidth < 992) {
        sidebar.classList.toggle("mobile-show")
      } else {
        sidebar.classList.toggle("collapsed")
        // Lưu trạng thái sidebar
        localStorage.setItem("sidebarCollapsed", sidebar.classList.contains("collapsed"))

        // Cập nhật margin cho main content và footer
        if (sidebar.classList.contains("collapsed")) {
          if (mainContent) {
            mainContent.style.marginLeft = "50px";
            mainContent.style.width = "calc(100% - 50px)";
          }
          if (adminFooter) {
            adminFooter.style.left = "50px";
            adminFooter.style.width = "calc(100% - 50px)";
          }
        } else {
          if (mainContent && savedSidebarWidth) {
            mainContent.style.marginLeft = savedSidebarWidth;
            mainContent.style.width = "";
          } else if (mainContent) {
            mainContent.style.marginLeft = "var(--sidebar-width)";
            mainContent.style.width = "";
          }
          
          if (adminFooter && savedSidebarWidth) {
            adminFooter.style.left = savedSidebarWidth;
            adminFooter.style.width = "";
          } else if (adminFooter) {
            adminFooter.style.left = "var(--sidebar-width)";
            adminFooter.style.width = "";
          }
        }

        // Khi mở lại sidebar từ trạng thái collapsed, khôi phục kích thước đã lưu
        if (!sidebar.classList.contains("collapsed") && savedSidebarWidth) {
          setTimeout(() => {
            sidebar.style.width = savedSidebarWidth
            document.documentElement.style.setProperty("--sidebar-width", savedSidebarWidth)
          }, 10)
        }
      }
    })
  }

  if (sidebarCollapseBtn) {
    sidebarCollapseBtn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      sidebar.classList.toggle("collapsed")
      // Lưu trạng thái sidebar
      localStorage.setItem("sidebarCollapsed", sidebar.classList.contains("collapsed"))

      // Cập nhật margin cho main content và footer
      if (sidebar.classList.contains("collapsed")) {
        if (mainContent) {
          mainContent.style.marginLeft = "50px";
          mainContent.style.width = "calc(100% - 50px)";
        }
        if (adminFooter) {
          adminFooter.style.left = "50px";
          adminFooter.style.width = "calc(100% - 50px)";
        }
      } else {
        if (mainContent && savedSidebarWidth) {
          mainContent.style.marginLeft = savedSidebarWidth;
          mainContent.style.width = "";
        } else if (mainContent) {
          mainContent.style.marginLeft = "var(--sidebar-width)";
          mainContent.style.width = "";
        }
        
        if (adminFooter && savedSidebarWidth) {
          adminFooter.style.left = savedSidebarWidth;
          adminFooter.style.width = "";
        } else if (adminFooter) {
          adminFooter.style.left = "var(--sidebar-width)";
          adminFooter.style.width = "";
        }
      }

      // Khi mở lại sidebar từ trạng thái collapsed, khôi phục kích thước đã lưu
      if (!sidebar.classList.contains("collapsed") && savedSidebarWidth) {
        setTimeout(() => {
          sidebar.style.width = savedSidebarWidth
          document.documentElement.style.setProperty("--sidebar-width", savedSidebarWidth)
        }, 10)
      }
    })
  }

  // Thêm tính năng điều chỉnh kích thước sidebar bằng chuột
  let isResizing = false
  let lastDownX = 0

  // Tạo phần tử resize handle mới
  const resizeHandle = document.createElement("div")
  resizeHandle.className = "sidebar-resize-handle-new"
  resizeHandle.style.position = "absolute"
  resizeHandle.style.top = "0"
  resizeHandle.style.right = "0"
  resizeHandle.style.width = "5px"
  resizeHandle.style.height = "100%"
  resizeHandle.style.cursor = "ew-resize"
  resizeHandle.style.zIndex = "1001"

  // Thêm vào sidebar
  sidebar.appendChild(resizeHandle)

  // Thêm sự kiện resize
  resizeHandle.addEventListener("mousedown", (e) => {
    if (sidebar.classList.contains("collapsed")) return

    isResizing = true
    lastDownX = e.clientX
    document.body.classList.add("no-select")
  })

  document.addEventListener("mousemove", (e) => {
    if (!isResizing) return

    const offsetRight = document.body.offsetWidth - (e.clientX - document.body.offsetLeft)
    const minWidth = 200 // Kích thước tối thiểu
    const maxWidth = 400 // Kích thước tối đa

    let newWidth = document.body.offsetWidth - offsetRight

    if (newWidth < minWidth) newWidth = minWidth
    if (newWidth > maxWidth) newWidth = maxWidth

    const newWidthPx = `${newWidth}px`
    sidebar.style.width = newWidthPx
    document.documentElement.style.setProperty("--sidebar-width", newWidthPx)

    // Lưu kích thước mới vào localStorage
    localStorage.setItem("sidebarWidth", newWidthPx)
  })

  document.addEventListener("mouseup", () => {
    isResizing = false
    document.body.classList.remove("no-select")
  })

  // Close sidebar when clicking outside on mobile
  document.addEventListener("click", (event) => {
    if (
      window.innerWidth < 992 &&
      sidebar &&
      sidebarToggle &&
      !sidebar.contains(event.target) &&
      !sidebarToggle.contains(event.target) &&
      sidebar.classList.contains("mobile-show")
    ) {
      sidebar.classList.remove("mobile-show")
    }
  })

  // Submenu Toggle
  const menuItems = document.querySelectorAll(".has-submenu")

  menuItems.forEach((item) => {
    const link = item.querySelector(".menu-link")

    link.addEventListener("click", (e) => {
      e.preventDefault()

      if (sidebar.classList.contains("collapsed") && window.innerWidth > 992) {
        return
      }

      item.classList.toggle("open")

      // Close other open submenus
      menuItems.forEach((otherItem) => {
        if (otherItem !== item && otherItem.classList.contains("open")) {
          otherItem.classList.remove("open")
        }
      })
    })
  })

  // Fullscreen Toggle
  const fullscreenToggle = document.getElementById("fullscreenToggle")

  if (fullscreenToggle) {
    fullscreenToggle.addEventListener("click", () => {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch((err) => {
          console.log(`Error attempting to enable full-screen mode: ${err.message}`)
        })
        fullscreenToggle.innerHTML = '<i class="fas fa-compress"></i>'
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen()
          fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i>'
        }
      }
    })
  }

  // Dark Mode Toggle
  const darkModeToggle = document.getElementById("darkModeToggle")
  const body = document.body

  // Check for saved dark mode preference
  if (localStorage.getItem("darkMode") === "enabled") {
    body.classList.add("dark-mode")
    if (darkModeToggle) {
      darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>'
    }
  }

  if (darkModeToggle) {
    darkModeToggle.addEventListener("click", () => {
      body.classList.toggle("dark-mode")

      if (body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled")
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>'
      } else {
        localStorage.setItem("darkMode", "disabled")
        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>'
      }
    })
  }

  // Responsive adjustments
  function handleResize() {
    if (window.innerWidth < 992 && sidebar) {
      sidebar.classList.add("collapsed")
      if (sidebar.classList.contains("mobile-show")) {
        sidebar.classList.remove("collapsed")
      }
    }
  }

  window.addEventListener("resize", handleResize)
  handleResize()

  // Active menu item
  const currentPath = window.location.pathname
  const menuLinks = document.querySelectorAll(".menu-link, .submenu a")

  menuLinks.forEach((link) => {
    const href = link.getAttribute("href")
    if (href && currentPath.includes(href) && href !== "#") {
      link.classList.add("active")

      // If it's a submenu item, open the parent menu
      if (link.closest(".submenu")) {
        const parentItem = link.closest(".menu-item")
        parentItem.classList.add("open")
      }
    }
  })

  // Add no-select class for preventing text selection during resize
  document.head.insertAdjacentHTML(
    "beforeend",
    `
    <style>
      .no-select {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
      }
      
      .sidebar-resize-handle-new {
        background-color: transparent;
        transition: background-color 0.2s;
      }
      
      .sidebar-resize-handle-new:hover {
        background-color: rgba(219, 112, 147, 0.3);
      }
      
      .sidebar.collapsed .sidebar-resize-handle-new {
        display: none;
      }
    </style>
  `,
  )
})
